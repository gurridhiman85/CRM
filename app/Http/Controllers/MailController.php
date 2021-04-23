<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Mail\SendReportEmail;
use Illuminate\Support\Facades\Mail;
 
class MailController extends Controller
{
    public function send()
    {
        $objDemo = new \stdClass();
        $objDemo->demo_one = 'Demo One Value';
        $objDemo->demo_two = 'Demo Two Value';
        $objDemo->sender = 'CRM Square Administrator';
        $objDemo->receiver = 'Gurri';
 
        Mail::to("gurri.dhiman85@gmail.com")->send(new DemoEmail($objDemo));
    }
}
