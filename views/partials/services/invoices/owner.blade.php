<style type="text/css">

    body {
        font-family: "Arial";
        font-size: 16px;
        color: #333;
        padding: 0;
        margin: 0;
    }

    table {
        border-spacing: 10px 4px;
    }

    td {
        padding: 2px;
    }

    .header {
        display: block;
        width: 760px;
        height: 60px;
        border-bottom: 1px solid #0099cc;
        text-align: justify;
        margin: 0 auto 15px;
        padding-top: 10px;
    }

    .logo1 {
        width: 32%;
        float: left;
    }

    .buttons {
        width: 22%;
        float: left;
        text-align: center;
    }

    .logo2 {
        width: 44%;
        float: right;
        text-align: right;
    }

    .data {
        border: 1px solid black;
        font-size: 14px;
    }

    .button.print {
        background: url(../assets/img/printer.png) center center no-repeat;
        background-size: 35px;
        margin-left: 10px;
        opacity: .7;
    }

    .button.print_pdf {
        background: url(../assets/img/pdf-file-format-symbol.png) center center no-repeat;
        background-size: 35px;
        opacity: .7;
    }

    .button {
        display: inline-block;
        width: 36px;
        height: 37px;
    }

    .footer {
        width: 750px;
        text-align: center;
        margin: 0 auto;
        border-top: 1px solid #0099cc;
        margin-top: 15px;
    }

    .table {
        border-top: 1px solid #0099cc;
        margin-top: 5px;
        padding-top: 15px;
        width: 750px;
        margin-left: auto;
        margin-right: auto;
    }

    @media print {

        body {
            padding: 0 !important;
            margin: 5mm 0 !important;
        }

        button {
            display: none;
        }

    }
</style>


<div>
    {!! $invoices !!}
</div>
