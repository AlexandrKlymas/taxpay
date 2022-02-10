webix.ui({
    container:"webix-container",
    rows:[
        {
            view:"datatable",
            autoheight: true,

            url: moduleConfig['url']+"action=main:getLogs",

            columns:[
                { id: "id", header: ["#",{"content":"serverFilter"}], width:"50","sort":"server" },
                { id: "license_plate", header: ["Номер ТЗ",{"content":"serverFilter"}],"sort":"server"},
                { id: "tax_number", header: ["ІПН",{"content":"serverFilter"}],fillspace:true,"sort":"server" },
                { id: "tech_passport", header: ["Техпаспорт",{"content":"serverFilter"}],fillspace:true,"sort":"server" },
                { id: "driving_license", header: ["Вод. посвідчення",{"content":"serverFilter"}],fillspace:true,"sort":"server" },
                { id: "driving_license_date_issue", header: ["Коли видане",{"content":"serverFilter"}],fillspace:true,"sort":"server" },
                { id: "fine_series", header: ["Серія постанови",{"content":"serverFilter"}],fillspace:true,"sort":"server" },
                { id: "fine_number", header: ["Номер постанови",{"content":"serverFilter"}],fillspace:true,"sort":"server" },
                { id: "created_at", header: ["Дата поиска",{"content":"serverFilter"}],fillspace:true,"sort":"server" },
            ],

            pager : {
                size:15,
                container :'pager',
                group:6,
                template:'{common.first()} {common.pages()}  {common.last()}',
            },
        }

    ]
})