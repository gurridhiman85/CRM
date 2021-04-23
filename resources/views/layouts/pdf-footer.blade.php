<!DOCTYPE html>
<html>
<body>
<style>
    body {
        margin-top: 2cm;
        margin-left: 0.50cm;
        margin-right: 0.50cm;
        margin-bottom: 2cm;
        background: none;
        font-family : Arial !important;
    }


    /** Define the footer rules **/
    .footer-part .page-date {
        position: relative;
    }

    /** Define the footer rules **/


    .footer-part a {

        top: 0.5cm;
        margin-top: 0.6cm;
        left: 0.5cm;
        right: 0.5cm;

        color: #a3a5a5;
        line-height: 1.5cm;
        float: left;
        padding-left: 0.5cm;
    }

    .footer-part a {
        margin-top: 0.3cm;
    }

</style>
<div class="footer-part">
    <a style="text-decoration: none;color: #9ea0a0;" target="_blank" href="http://www.datasquare.com"><img style="height: 0.4cm;line-height: 1.2cm;margin-top:0.7cm;margin-bottom:0.25cm;text-align:left;" src="https://crmsquare.com/DB_CRMLV_v16D_AB/img/crmlogo.png"></img></a>
    <span style="height: 0.4cm;line-height: 1.2cm;margin-top:0.7cm;margin-bottom:0.25cm;text-align:center;" class="page-date">{!! $footer !!}</span>
    <span style="bottom: 0cm; left: 0cm; right: 0.5cm;height: 2cm;color: #9ea0a0;line-height: 1.5cm;float:right;">{!! date('Y-m-d') !!}</span>
</div>
</body>
</html>