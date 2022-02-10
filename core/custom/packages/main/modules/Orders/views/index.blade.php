@extends('Modules.Orders::layout')
@section('content')


    <div id="webix-container"></div>
    <div id="pager"></div>

    <script>


        var moduleUrl = '{!! $moduleUrl !!}';

        var statusesList = {!! json_encode($statuses,JSON_UNESCAPED_UNICODE) !!};




        function updateRow(orderId) {
            $.get(moduleUrl+"action=main:loadOrder",{
                orderId
            },function (response) {
                if(response.status === 'success'){

                    $$('orders').updateItem(orderId, response.data);
                }
                else{
                    alert('Произошла ошибка')
                }
            })
        }


        function serviceOrderFormLoadOrChange() {
            var values = $$('serviceOrderForm').getValues();
            var status = values['status'];

            $$('serviceOrderFormSetPaidStatus').hide();
            $$('serviceOrderFormSetConfirmedStatus').hide();

            switch (status){
                case 'new':
                    $$('serviceOrderFormSetPaidStatus').show();
                    break;
                case 'paid':
                    $$('serviceOrderFormSetConfirmedStatus').show();
                    break;
            }
        }



        var form = {
            view:"scrollview",
            height:430,
            body:{
                rows:[
                    {
                        view: "form",
                        id: "serviceOrderForm",
                        "elements":[
                            {
                                cols:[
                                    {
                                        rows:[
                                            { labelWidth:100, view:"text",label:"ID",name:"id",},
                                            { labelWidth:100, view:"select",  name:"service_id", disabled:true,  label:"Услуга", options: {!! json_encode($services) !!},  },
                                            { labelWidth:100, view:"select",  name:"status",   label:"Статус",  options: statusesList },
                                            { labelWidth:100, view:"datepicker",  timepicker: true, name:"liqpay_payment_date",  label:"Дата оплаты",  format:"%d.%m.%Y %H:%i:%s"  },
                                            { labelWidth:100, view:"text",label:"ФИО",name:"full_name",},
                                        ]
                                    },
                                    {
                                        rows:[

                                            { labelWidth:100, view:"text",label:"Транзакции",name:"total",},
                                            { labelWidth:100, view:"text",label:"Оплата",name:"sum",},
                                            { labelWidth:100, view:"text",label:"Liqpay",name:"liqpay_real_commission",},
                                            { labelWidth:100, view:"text",label:"TK",name:"bank_commission",},
                                            { labelWidth:100, view:"text",label:"GP",name:"profit",},
                                        ]
                                    }
                                ],

                            },



                        ],
                        on:{
                            onChange:serviceOrderFormLoadOrChange,
                            onAfterLoad:serviceOrderFormLoadOrChange,
                        }

                    },{
                        view: "datatable",
                        id:"tableRecipient",

                        editable:true,

                        columns:[
                            { id: "mfo", header: "МФО", width:"50",  editor:"text", },
                            { id: "account", header: "Счет", width:"235",  editor:"text" },
                            { id: "edrpou", header: "ЕГРПОУ",width:"80",  editor:"text" },
                            { id: "purpose", header: "Назначение",  fillspace:13,editor:"text" },
                            { id: "recipient_name", header: "Название",  fillspace:5,editor:"text" },
                            { id: "recipient_bank_name", header: "Банк",  fillspace:5,editor:"text" },

                            { id: "amount", header: "Сумма",  width:"60",editor:"text" },

                            { id: "recipient_type", editor:"select", header: "Тип", options: {!! json_encode($recipient_types) !!} },
                            { id: "status",  header: "Статус", options: {!! json_encode($recipient_statuses) !!} },
                        ],

                        scheme:{
                            $change:function(item){
                                item.$css = '';
                                /*
                                    const STATUS_WAIT = 'wait';
                                    const STATUS_CONFIRMED = 'confirmed';
                                    const STATUS_HELD = 'held';
                                    const STATUS_FINISHED = 'finished';
                                    const STATUS_ERROR = 'error';
                                 */

                                if(item.status  === 'confirmed'){
                                    item.$css = 'primary-status';
                                }
                                if(item.status  === 'confirmed'){
                                    item.$css = 'yellow-status';
                                }

                                if(item.status  === 'held'){
                                    item.$css = 'yellow-status';
                                }
                                if(item.status  === 'finished'){
                                    item.$css = 'green-status';
                                }

                                if(item.status  === 'error'){
                                    item.$css = 'red-status';
                                }


                            }
                        },


                        autoheight: true,

                    },
                    { labelWidth:100, view:"button",label:"Сохранить",click:"saveServiceOrder"},
                ]

            }
        }



        function saveServiceOrder(){
            var serviceOrder = $$("serviceOrderForm").getValues();

            var recipients = [];

            $$("tableRecipient").eachRow(function (row) {
                recipients.push($$("tableRecipient").getItem(row));
            });

            webix.ajax().post("{!! $moduleUrl !!}action=order:save", {
                serviceOrder:serviceOrder,
                recipients:recipients
            }, function(response){
                response = JSON.parse(response);


                if(response.status === 'success'){
                    webix.message('Сохранено');


                    loadForm(serviceOrder['id']);
                    updateRow(serviceOrder['id'])

                }
                else{
                    webix.alert({
                        title:"Произошла ошибка: "+response.error,
                        type:"alert-warning"
                    })
                }
            });

        }





        new webix.ui({
            container:'webix-container',
            rows:[
                {
                    cols:[
                        { view:"button", type:"icon", icon:"wxi-trash",  label:"<i>Удалить</i>", width:110, click:"delRow" },
                        { view:"button", type:"icon", icon:"wxi-pencil",  label:"<i>Правка</i>", width:110, click:"editRow" },
                        { view:"button", type:"icon", icon:"wxi-refresh",  label:"<i>Обновить</i>", width:110, click:"refresh" },
                        { view:"button", type:"icon", icon:"wxi-file",  label:"<i>Сделать PDF</i>", width:140, click:"makePdf" },
                        { view:"button", type:"icon", icon:"wxi-comment",  label:"<i>LiqpayResponce</i>", width:145, click:"showLiqpayResponse" },
                        { view:"button", type:"icon", icon:"wxi-comment",  label:"<i>История</i>", width:145, click:"showHistory" },
                        { view:"button", type:"icon", icon:"wxi-download", label:"<i>Експорт в Excel</i>", click:"exportToExcel", width:145 }
                    ]
                },
                {
                  cols:[
                      { view:"datepicker", id:"payment_data_from", width:300, labelWidth:100, label: "Дата оплаты c", format:"%d.%m.%Y",on:{ onChange:function () {
                                 $$('orders').filterByAll()
                              }
                        }
                      },
                      { view:"datepicker", id:"payment_data_to", width:300, labelWidth:100,label: "Дата оплаты по",  format:"%d.%m.%Y", on:{ onChange:function () {
                                  $$('orders').filterByAll()
                              }
                        }
                      },
                      {}
                  ]
                },
            ]
        });

        new webix.ui({
            view: "datatable",
            select:true,
            multiselect:false,

            id:"orders",


            columns:[
                {
                    "id":"checkedCol", "header":["",{"content":"masterCheckbox"}], "editor":"checkbox",
                    "checkValue":1,"uncheckValue":0,"template":"{common.checkbox()}", width:30
                },

                {
                    "id":"id","header":["Id",{"content":"serverFilter"}],"sort":"server", width:55
                },

                {
                    id:"service_id",header:["Форма",{"content":"serverSelectFilter"}],sort:"server",
                    editor: "select", options: {!! json_encode($services) !!}, width:150
                },

                {
                    "id":"recipient_name","header":["Получатель",{"content":"serverFilter"}], fillspace:true, minWidth:150,
                },
                {
                    id:"status","header":["Статус",{"content":"serverSelectFilter"}],"sort":"server",
                    editor: "select", options: statusesList,  width:120
                },


                {
                    id:"liqpay_transaction_id","header":["ID оплаты",{"content":"serverFilter"}],"sort":"server", width:110,
                },
                {
                    id:"liqpay_payment_date","header":["Дата оплаты",{"content":"serverFilter"}],"sort":"server", width:150,
                },

                {
                    "id":"full_name","header":["ФИО",{"content":"serverFilter"}],"sort":"server", fillspace:true, minWidth:250,
                },
                {
                    "id":"phone","header":["телефон",{"content":"serverFilter"}],"sort":"server", width:120
                },
                {
                    "id":"email","header":["E-mail",{"content":"serverFilter"}],"sort":"server", width:200
                },

                {
                    "id":"total","header":["Транзакции",{"content":"serverFilter"}],"sort":"server", width:85
                },
                {
                    "id":"sum","header":["Оплата",{"content":"serverFilter"}],"sort":"server", width:65
                },
                {
                    "id":"liqpay_real_commission","header":["Liqpay",{"content":"serverFilter"}],"sort":"server", width:60
                },

                {
                    "id":"bank_commission","header":["TK",{"content":"serverFilter"}],"sort":"server", width:50
                },

                {
                    "id":"profit","header":["GP",{"content":"serverFilter"}],"sort":"server", width:50
                },

                {
                    "id":"virtual_pdf","header":["ПДФ"], width:75,
                    template:function (obj) {

                        if(obj.invoice_file_pdf){
                            return '<a href="/'+obj.invoice_file_pdf+'" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></>';
                        }
                        return  '';
                    }
                },

            ],

            on:{
                onAfterRender: function(){
                    changeOptionHandler();
                },
                onCollectValues:function(id, data){

                    if (id === "status"){
                        data.values = statusesList.slice(0);;
                        // create sorting method with built-in features
                        data.values.sort( webix.DataStore.prototype.sorting.create({ as:"string", by:"index", dir:"asc" }) );
                    }
                }
            },

            scheme:{
                $change:function(item){

                    switch (item.status) {
                        case 'wait':
                        case 'error':
                        case 'failure':
                        case 'reversed':
                        case 'question':
                            item.$css = 'red-status';
                            break;

                        case 'success':
                        case 'submitted':
                        case 'ready':
                            item.$css = 'green-status';
                            break;
                    }





                }
            },

            url: {
                $proxy: true,
                load: function (view, params) {

                    if(params === null){
                        params = {};
                    }

                    if(typeof params.filter !=="object"){
                        params.filter = {};
                    }

                    params.filter = inserPaymentDateFilters(params.filter);

                    return webix.ajax().get("{!! $moduleUrl !!}action=main:loadOrders", params);
                },

            },

            save: {
                delete:  "{!! $moduleUrl !!}action=order:delete"
            },

            {{--url:"{!! $moduleUrl !!}action=main:loadOrders",--}}



            autoheight:true,
            autowidth:false,

            pager : {
                size:15,
                container :'pager',
                group:6,
                template:'{common.first()} {common.pages()}  {common.last()}',
            },
        })

        function changeOptionHandler(){
            var node = $$("orders").getHeaderNode('status', 1).querySelectorAll("select option");

            node.forEach(function(item,index,arr){
                switch(item.value) {
                    case 'wait':
                    case 'error':
                    case 'failure':
                    case 'reversed':
                    case 'question':
                    case 'sandbox':
                        item.classList.add('red-status-select');
                        break;
                    case 'success':
                    case 'submitted':
                    case 'ready':
                        item.classList.add('green-status-select');
                        break;
                }
            });
        }


        var $$orders = $$('orders');

        var editFormModal = webix.ui({
            view:"window",
            modal:true,
            id:"my_win",

            width:window.innerWidth - 50,

            head:{
                view:"toolbar",  cols:[
                    { view:"label", label: "Редактирование заказа услуги" },

                    { view:"icon", icon:"wxi wxi-close", click:function(){
                            $$('my_win').hide();
                        }}
                ]
            },

            position:"center",


            body:form
        });


        function inserPaymentDateFilters(filter){

            var paymentDateFrom,paymentDateTo;

            if($$('payment_data_from').getValue()){
                paymentDateFrom =  new Date($$('payment_data_from').getValue()).toDateString()

            }
            if($$('payment_data_to').getValue()){
                paymentDateTo =  new Date($$('payment_data_to').getValue()).toDateString()
            }


            if(paymentDateFrom){
                filter.payment_data_from = paymentDateFrom;
            }
            if(paymentDateTo){
                filter.payment_data_to = paymentDateTo;
            }
            return filter;
        }
        function makePdf() {
            var selectedRow = $$orders.getSelectedId();

            if(selectedRow === undefined){
                alert('Ничего не выбрано');
                return;
            }

            var orderId = selectedRow.id;

            webix.ajax("{!! $moduleUrl !!}action=order:makePdf&orderId="+orderId).then(function(response){
                response = response.json();
                if(response.status==='success'){
                    updateRow(orderId)
                }
                else{
                    alert('произошла ошибка')
                }

            });
        }

        function delRow(){
            var selected = $$orders.getSelectedId();
            if (typeof(selected) !== "undefined") {
                webix.confirm("Вы уверены, что хотите удалить выбранную строку?", "confirm-warning", function(result){
                    if (result === true) {
                        $$orders.remove(selected);
                    }
                });
            } else {
                webix.alert("Вы не выбрали строку для удаления", "alert-warning", function(result){});


            }
        }




        function loadForm(orderId) {
            $$("tableRecipient").clearAll()
            $$("serviceOrderForm").load("{!!  $moduleUrl  !!}action=order:loadDataForOrderForm&orderId="+orderId);
            $$("tableRecipient").load("{!!  $moduleUrl  !!}action=order:loadRecipients&orderId="+orderId);

            setTimeout(function () {
                editFormModal.show()
            },500)
        }

        function showLiqpayResponse() {
            var selectedRow = $$orders.getSelectedId();

            if(selectedRow === undefined){
                alert('Ничего не выбрано');
                return;
            }
            var orderId = selectedRow.id;

            $.get(moduleUrl+"action=order:getLiqpayResponse",{
                orderId
            },function (response) {
                if(response.status==='success'){

                    var body = "<div class='liqpay-preview'>";

                    for(var key in response.liqpayResponse){
                        var value = response.liqpayResponse[key];

                        body += "<p><b>"+key+": </b> "+value+"</p>";
                    }

                    body +='</div>';
                    webix.ui({
                        view: "window",
                        modal: true,
                        head: "Liqpay Response",
                        close: true,
                        minWidth: 600,
                        minHeight: 600,
                        position: "center",
                        body: {
                            view: "scrollview",
                            scroll: "xy",
                            autoheight:true,
                            body: {
                                template: body,
                                autoheight:true,

                            }
                        }
                    }).show()
                }
                else{
                    alert('Произошла ошибка')
                }
            })
        }

        function exportToExcel() {

            var table = $$('orders');
            var checkedRowIds = [];

            var filter = {};


            table.eachRow(function(row){
                if(!row){
                    return;
                }

                var record = table.getItem(row);


                if(record['checkedCol']){
                    checkedRowIds.push(record['id'])
                }
            });

            table.eachColumn(function(columnId){
                var value = table.getFilter(columnId);
                value = $(value).val();
                if(value){
                    filter[columnId] = value;
                }
            })

            filter = inserPaymentDateFilters(filter);

            var url = moduleUrl+"action=main:exportToExcel";

            for(filterField in filter){
                var value = filter[filterField];

                if(value){
                    url += "&filter["+filterField+"]="+encodeURIComponent(value)
                }
            }

            if(checkedRowIds){
                url+= "&checked="+checkedRowIds.join(',');
            }

            window.open(url,"_blank")





            // $.get(moduleUrl+"action+main:exportToExcel",{
            //     checked:checkedRowIds,
            //     filter:filter,
            // },function () {
            //
            // })



        }
        function refresh() {
            location.reload();
        }
        function editRow() {
            var selectedRow = $$orders.getSelectedId();

            if(selectedRow === undefined){
                alert('Ничего не выбрано');
                return;
            }

            var orderId = selectedRow.id;
            loadForm(orderId)

        }

        function showHistory() {
            var selectedRow = $$orders.getSelectedId();

            if(selectedRow === undefined){
                alert('Ничего не выбрано');
                return;
            }
            var orderId = selectedRow.id;

            $.get(moduleUrl+"action=order:getHistory",{
                orderId
            },function (response) {
                if(response.status==='success'){

                    var body = "<div class='liqpay-preview'>";

                    for(var key in response.history){
                        var value = response.history[key];

                        body += "<p><b>"+key+": </b> "+value+"</p>";
                    }

                    body +='</div>';
                    webix.ui({
                        view: "window",
                        modal: true,
                        head: "История",
                        close: true,
                        minWidth: 600,
                        minHeight: 600,
                        position: "center",
                        body: {
                            view: "scrollview",
                            scroll: "xy",
                            autoheight:true,
                            body: {
                                template: body,
                                autoheight:true,

                            }
                        }
                    }).show()
                }
                else{
                    alert('Произошла ошибка')
                }
            })
        }



    </script>
@endsection