<?php

namespace App\Console\Commands;

use App\Mail\DashboardLinks;
use http\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Mail;

class createDashboardLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:dashboardlinks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tTodayDateTime = date('Y-m-d H:i:s');
        $lLastdateTime = date('Y-m-t H:i:s',strtotime($tTodayDateTime));
        $dashboard_types = DB::select("SELECT * FROM ZChart_Links");
        $linksArray = [];
        foreach ($dashboard_types as $dashboard_type){
            $linkString = Str::random(10);
            $link = url('/').'/'.$linkString;
            DB::update("UPDATE ZChart_Links SET link = '".$linkString."', expire_at = '".$lLastdateTime."' WHERE name = '".$dashboard_type->name."'");
            array_push($linksArray,[
                'name' => $dashboard_type->name,
                'link' => $link,
                'expire_at' => $lLastdateTime
            ]);
        }

        $objDemo = new \stdClass();
        $objDemo->links = $linksArray;
        $objDemo->To = 'devyani@datasquare.com';
        $objDemo->Cc = 'gurri.dhiman85@gmail.com';
        $objDemo->Bcc = '';
        $objDemo->Sub = 'Month - '. date('F').' Dashboard Links';
        $objDemo->limitedtextarea1 = 'Followings are the new generated links for dashboard:';

        $objDemo->sender = 'Data Square Support Team';
        $objDemo->senderEmail = 'esupport@datasquare.com';
        $objDemo->receiver = 'Devyani Sadh';

        Mail::to('gurri.dhiman85@gmail.com')->send(new DashboardLinks($objDemo));

        if (count(Mail::failures()) > 0) {
            $emsg = "There was one or more failures. They were: <br />";
            foreach (Mail::failures() as $email_address) {
                $emsg .= " - $email_address <br />";
            }
        }
    }
}
