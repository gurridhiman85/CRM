<!DOCTYPE html>
<html>
<style>
    body {
        margin-top: 2cm;
        margin-left: 0.50cm;
        margin-right: 0.50cm;
        margin-bottom: 2cm;
        background: none;
        font-family : Arial !important;
    }

    table{
        position: inherit;
        /*line-height: 8cm;	*/
        top:2cm;
        text-align: center;
        left: 0.5cm;
        right: 0.5cm;
        float : none !important;
        width : 100% !important;

    }

    .contentDiv{
        /*line-height: 8cm;*/
    }

    .cimg{
        text-align: center;
        left: 0.5cm;
        right: 0.5cm;
    }

    /*     Custom c&P                */
    .table-bordered, .table-bordered td, .table-bordered th {
        border: 1px solid #e9ecef;
    }

    .table {
        width: 100%;
        color: #000000;
        margin-bottom: 3cm;
        margin-top: 0.8cm;
    }

    table {
        border-collapse: collapse;

    }

    .color-table.sr-table thead th {
        background-color: #f9f9f9;
        color: #010101;
        border: 0.5px solid #e9ecef;
        padding: 5px 8px;
        font-size: 12px;
        font-weight:400;
    }

    .table thead th {
        vertical-align: middle;
    }

    .color-table.sr-table td {
        border: 1px solid #e9ecef;
        padding: 5px 8px;
        font-size: 8pt;
    }

    .color-table.sr-table td.left-side-cell {
        border: 1px solid #e9ecef;
        padding: 5px 8px;
        font-size: 12px !important;
        font-weight:400 !important;
        color: #010101 !important;
    }

</style>
<body>
<div class="pdfPageBody">
    <table class="table table-bordered table-hover color-table sr-table" border="1">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
        </tr>
        </thead>
        <tbody>
        @for($i = 0; $i < 100;$i++)
            <tr>
                <td>Gurri</td>
                <td>gurri.dhiman85</td>
                <td>SBS Nagar VPO bhullana distt. Kapurthala</td>
            </tr>
        @endfor
        </tbody>
    </table>
</div>
</body>
</html>