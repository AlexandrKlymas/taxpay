<!DOCTYPE HTML>
<html>
    <head>
    <link rel="stylesheet" href="[+module_url+]css/font-awesome.min.css?v=4.7.0">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700&amp;subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="[+module_url+]skin/webix.css" type="text/css">
    <style>
        body.webix_full_screen{overflow:auto !important;}
        .webix_view.webix_pager{margin-bottom:30px;}
        .webix_cell{-webkit-transition: all .3s,-moz-transition: all .3s,-o-transition: all .3s,transition: all .3s}
        .webix_cell:nth-child(odd){background-color:#f6f8f8;}
        .webix_cell:hover{background-color: rgba(93, 109, 202, 0.16);}
        .webix_modal_box{width:340px;}
		.green-status{color: #000; background-color: rgba(144, 239, 184, 0.7) !important;}/*#27ae60*/
		.red-status{color: #333; background:#f1d7d7 !important;}/*#FFAAAA*/
		.webix_row_select{background: #eee !important; color: #333 !important;}
		.red-status-select{color: #f56d6d;}/* background:#efbaba; #FFAAAA*/
		.green-status-select{color: #27ae60;font-weight: bold;} /*background-color: rgba(39,174,96,0.7) #27ae60*/
		
		/* On screens that are 992px or less, set the background color to blue */
		.webix_layout_toolbar.webix_toolbar .webix_control.webix_el_button i{
			font-style: normal;
		}
		.webix_layout_toolbar.webix_toolbar .webix_control.webix_el_button[view_id] span{
			margin-left: 6px;
		}
		@media screen and (max-width: 992px) {
			.webix_layout_toolbar.webix_toolbar .webix_control.webix_el_button[view_id],
			.webix_layout_toolbar.webix_toolbar .webix_control.webix_el_button[view_id] .webix_el_box{
				width: 60px !important
			}
			.webix_layout_toolbar.webix_toolbar .webix_control.webix_el_button[view_id] span{
				margin-left: 20px;
			}
			.webix_layout_toolbar.webix_toolbar .webix_control.webix_el_button[view_id] i{
				display:none;
			}
			
			.webix_layout_toolbar .webix_el_button{
				
			}
		}

		/* On screens that are 600px or less, set the background color to olive */
		@media screen and (max-width: 600px) {
		  body {
			background-color: olive;
		  }
		}
		
    </style>
    <script src="[+module_url+]skin/webix.js" type="text/javascript"></script>
    <script src="//cdn.webix.com/site/i18n/ru.js" type="text/javascript" charset="utf-8"></script>
    </head>
    <body style="background-color: #fafafa;width:97%;">
        <div id="wbx_table" style="padding-bottom:20px;"></div>
		<div id="wbx_pp" style="padding-bottom:20px;width:90%;"></div>
    
        <script type="text/javascript" charset="utf-8">
		//temp data collection for exporting checked records
		var temp = new webix.DataCollection({});
        webix.ready(function(){
            webix.i18n.setLocale("ru-RU");
            webix.editors.$popup = {
                date:{
                    view:"popup",
                    body:{ 
                        view:"calendar", 
                        timepicker:true, 
                        timepickerHeight:50,
                        width: 320, 
                        height:300
                    }
                },
                text:{
                    view:"popup", 
                    body:{view:"textarea", width:350, height:150}
                }
            };
		
		var form = {
			view:"form",
			id:"myform",
			borderless:true,
			elements: [
				[+formfields+] ,
				{ margin:5, cols:[
					{ view:"button", value: "Сохранить", type:"form", click:submit_form},
					{ view:"button", value: "Закрыть", click: function (elementId, event) {
						this.getTopParentView().hide();
					}}
				]},
				{rows : [
					{template:"The End:)", type:"section"}
				]}
			],
			rules:{
				"email":webix.rules.isEmail,
				"login":webix.rules.isNotEmpty
			},
			elementsConfig:{
				labelPosition:"top",
			},
			height:500,
			scroll:"y"
		};
		var search_form = {
			view:"form",
			id:"searchform",
			/*borderless:true,*/
			elements: [
				{ margin:5, cols:[
					[+search_formfields+],
					{ view:"button", type:"iconButton", icon:"search", label:"Найти", click:"add_search", width:100}
				]},
			]
		};
		webix.ui({
            view:"window",
            id:"win2",
            width:500,
			height:500,
            position:"center",
            modal:true,
            head:{
					view:"toolbar", margin:-4, cols:[
						{view:"label", label: "Редактирование данных" },
						{ view:"icon", icon:"times-circle",
							click:"$$('win2').hide();"}
						]
				},
            body:form
        });
			
        webixTable = new webix.ui({
				width: (window.innerWidth < 1024 ) ? '': window.innerWidth - 23,	   
                container:"wbx_table",
				id:"top",
                rows:[
                    { view:"template", type:"header", template:"[+name+]"},
                    { view:"toolbar", id:"mybar", responsive:"mybar", elements:[
                        // { view:"button", type:"iconButton", icon:"plus", label:"<i>Добавить</i>", width:110, click:"add_row"},
                        // { view:"button", type:"iconButton", icon:"trash",  label:"<i>Удалить</i>", width:110, click:"del_row" },
                        // [+modal_edit_btn+]
                        { view:"button", type:"iconButton", icon:"refresh",  label:"<i>Обновить</i>", width:110, click:"refresh" },
						// { view:"button", type:"iconButton", icon:"file",  label:"<i>Сделать PDF</i>", width:140, click:"make_pdf" },
						{ view:"button", type:"iconButton", icon:"comment",  label:"<i>LiqpayResponce</i>", width:145, click:"show_liqpay_responce" },
						{ view:"button", type:"iconButton", icon:"download", label:"<i>Експорт в Excel</i>", width:145, click:function(){

						 var to_export = [];
						  $$("mydatatable").data.each(function(obj, i, arr){
							if(obj.checkedCol){
							  for(var i in obj){
								if (i != '$css') {
								//console.log($$("mydatatable").getColumnConfig(i), i);
								var tpl = $$("mydatatable").getColumnConfig(i).template;
									if (tpl) {
										obj[i] = tpl(obj, $$("mydatatable").type, obj[i], $$("mydatatable").getColumnConfig(i));
									}
								}
							  }
							  to_export.push(obj);
							}
						  });
						  temp.clearAll();
						  temp.parse(to_export);
//checkedCol,id,form_id,pay_info,status,payment_id,create_date,fio,itogo,summ_original,receiver_commission,summ_bank,summ_ostatok,href_order_pdf
						  webix.toExcel(temp, {
							columns:[
							  {id:"id"},
							  // {id:"form_id", header:"Форма"},
							  {id:"poluch_name", header:"Получатель"},
							  {id:"status", header:"Статус"},
								{id:"payment_id", header:"Id оплаты"},
								{id:"create_date", header:"Дата оплаты"},
								{id:"fio", header:"ФИО"},
								{id:"phone", header:"телефон"},
								{id:"email", header:"Email"},
								{id:"itogo", header:"Транзакция"},
								// {id:"summ_original", header:"Оплата"},
								// {id:"receiver_commission", header:"LiqPay"},
								// {id:"summ_bank", header:"TK"},
								// {id:"summ_ostatok", header:"GP"}
							]
						  });


						}}
                        ]
                    },
					search_form,
                    { view:"datatable",
					 	scheme:{
							$change:function(item){
								switch (item.status) {
									case 'Оплачено':
									// case 'Підтверджено':
									// case 'Проведено':
										item.$css = "green-status";
										break;
									// case 'Тестовий платіж':
									case 'Ожидает оплати':
									case 'Ошибка':
									// case 'Забраковано (failure)':
									// case 'Платіж повернуто':
									// case 'Нестандартная ситуація':
										item.$css = "red-status";
										break;
									}
							}
						},
                        autoheight:true,select:"row",resizeColumn:true,
                        id:"mydatatable",
                        editable:[+inline_edit+],
                        editaction: "dblclick",
                        datafetch:[+display+],
                        navigation:true,
                        columns : [+cols+],
						on:{
							onBeforeFilter:function(){
							  var activeNode = document.activeElement; 
							  if (activeNode.tagName == 'INPUT')
								activeColumn = this.columnId(activeNode.closest('td[column]').getAttribute('column'));  
							},
							onAfterFilter:function(){
							  if (activeColumn){
								this.getFilter(activeColumn).focus();    
								var caret = this.getFilter(activeColumn).value.length;
								// since IE9
								this.getFilter(activeColumn).setSelectionRange(caret, caret);
								activeColumn = null;
							  }  	
							},
							onAfterRender: function(){
								changeOptionHandler();
							}
						},		   
                        pager:{   
                            size : [+display+],
                            group : 5,
                            template : "{common.first()} {common.prev()} {common.pages()} {common.next()} {common.last()}",
							container:"wbx_pp"
                        },
                        url: "[+module_url+]action.php?action=list&module_id=[+module_id+]",
                        save: "[+module_url+]action.php?action=update&module_id=[+module_id+]",
                        delete: "[+module_url+]action.php?action=delete&module_id=[+module_id+]"
                    }
                ]
            });
			/*$$("mydatatable").attachEvent("onBeforeFilter", function(id, value, config){
				if (id == 'create_date') {
					alert(config);
				}
			})*/
			$$("mydatatable").attachEvent("onBeforeLoad", function(){
				$$("mydatatable").config.url = $$("mydatatable").config.url + '&hhhhh=kkkk';
				/*alert('load');*/
			})
			
			function changeOptionHandler(){
				var node = $$("mydatatable").getHeaderNode('status', 1).querySelectorAll("select option");
				node.forEach(function(item,index,arr){
					switch(item.value) {
                        case 1:
						// case 'wait':
						// case 'error':
                        case 3:
						// case 'failure':
						// case 'reversed':
						// case 'question':
						// case 'sandbox':
							item.classList.add('red-status-select');
							break;
						// case 'success':
                        case 2:
						// case 'submitted':
						// case 'ready':
							item.classList.add('green-status-select');
							break;
					}
				});
			}
			window.addEventListener("resize", function(e) {
				webixTable.define("width", document.body.clientWidth);
				webixTable.resize();
			}, false);
        });


        function add_row() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '[+module_url+]action.php?action=get_next&module_id=[+module_id+]', false);
            xhr.send();
            if (xhr.status != 200) {
                  show_alert(xhr.status + ': ' + xhr.statusText, "alert-warning");
            } else {
                var ins = {'[+idField+]' : xhr.responseText};
                $$("mydatatable").add(ins, 0);
            }
        }
        function del_row(){
            var selected = $$("mydatatable").getSelectedId();
            if (typeof(selected) !== "undefined") {
                webix.confirm("Вы уверены, что хотите удалить выбранную строку?", "confirm-warning", function(result){
                    if (result === true) {
                        $$("mydatatable").remove(selected);
                    }
                });
            } else {
                show_alert("Вы не выбрали строку для удаления", "alert-warning");
            }
        }
        function refresh(str) {
			//сбрасываем все фильтры при клике на кнопку refresh
			var allFilters = document.querySelectorAll('.webix_view input[type="text"], .webix_view select');
			allFilters.forEach(function(item, index, object){ 
				object[index].value = '';
			});
            $$("mydatatable").clearAll();
            $$("mydatatable").load($$("mydatatable").config.url + str);
        }
        function edit_row(){
            var selected = $$("mydatatable").getSelectedId();
            if (typeof(selected) !== "undefined") {
                $$("win2").getBody().clear();
				$$("win2").show();
				$$("myform").load("[+module_url+]action.php?action=get_row&module_id=[+module_id+]&key=" + selected);
				/*$$("win2").getBody().focus();*/
            } else {
                show_alert("Вы не выбрали строку для редактирования", "alert-warning");
            }
        }
		function submit_form() {
			webix.ajax().post("[+module_url+]action.php?action=update_row&module_id=[+module_id+]", $$("myform").getValues(), function(text, data, xhr){ 
				if (text == 'ok') {
					refresh();
					show_alert('Изменения успешно сохранены', "alert-success");
				} else {
					show_alert('Ошибка на сервере, попробуйте позднее', "alert-warning");
				}
			});
		}
		function make_pdf() {
			var selected = $$("mydatatable").getSelectedId();
			if (typeof(selected) !== "undefined") {
				var xhr = new XMLHttpRequest();
				xhr.open('GET', '[+module_url+]action.php?action=make_pdf&id=' + selected + '&module_id=[+module_id+]', false);
				xhr.send();
				if (xhr.status != 200) {
					show_alert('Ошибка на сервере, попробуйте позднее', "alert-warning");
				} else {
					refresh();
					show_alert('Изменения успешно сохранены.<br><a href="[+site_url+]'+ xhr.responseText+'" target="_blank">Скачать</a> ', "alert-success");
				}
			} else {
                show_alert("Вы не выбрали строку для создания PDF", "alert-warning");
            }
		}
		function show_liqpay_responce(){
			var selected = $$("mydatatable").getSelectedId();
			if (typeof(selected) !== "undefined") {
				var xhr = new XMLHttpRequest();
				xhr.open('GET', '[+module_url+]action.php?action=show_liqpay_responce&id=' + selected + '&module_id=[+module_id+]', false);
				xhr.send();
				if (xhr.status != 200) {
					show_alert('Ошибка на сервере, попробуйте позднее', "alert-warning");
				} else {
					show_alert('<b>LiqpayResponce</b><br><br><div style="max-height:400px;overflow-y:auto;padding:0 20px;text-align:left;">' + xhr.responseText + '</div>', "alert-success");
				}
			} else {
                show_alert("Вы не выбрали строку для просмотра ответа", "alert-warning");
            }
		}
		function exportdata() {
					
			var checked = [];
			$$("mydatatable").eachRow(function(id){
				var row = this.getItem(id);
				var check = row.checkedCol;
				if(check !== null || check !== undefined) {
					checked.push(row);
				}
			});		
		   webix.toExcel(checked, {
           		filename: "userdata",
				filter:function(obj){
					var found = sel.find(function(item){
					console.log(123);
						return item.checkedCol == obj.checkedCol;
					});
					return found;
				}
				//download:true
				
           });	
        }				
		function add_search() {
			var obj = $$("searchform").getValues();
			var str = '';
			for (key in obj) {
				str = str + '&' + key + '=' + obj[key];
			}
			/*alert(str);*/
			refresh(str);
		}
        function show_alert(text, level) {
            webix.alert(text, level, function(result){});
        }
        </script>
    </body>
</html>