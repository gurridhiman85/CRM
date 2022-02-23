<?php

namespace App\Http\Controllers;

use App\Library\Ajax;
use App\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View as View;
use Illuminate\Support\Facades\Artisan;
use Crypt;
use Validator;
use Auth;
use DB;
use Session;

class UserController extends Controller
{
    public static function check_valid_pass($candidate, $uUsername, $fFirstname, $lLastname)
    {    // validate password for not used username ,firstname and lastname.
        /* if (!preg_match_all('$\S*(?=\S*['.$uUsername.'])(?=\S*['.$fFirstname.'])(?=\S*['.$lLastname.'])\S*$', $candidate))
             return True;
         else
             return false;*/
        if (preg_match('/' . $uUsername . '/', $candidate)) {
            return false;
        } elseif (preg_match('/' . $fFirstname . '/', $candidate)) {
            return false;
        } elseif (preg_match('/' . $lLastname . '/', $candidate)) {
            return false;
        } elseif (preg_match('/dictionary/', $candidate)) {
            return false;
        } else {
            return true;
        }
    }

    public static function valid_pass($candidate)
    {  // Password validation , Password should be in proper format.
        /*
            Explaining $\S*(?=\S{4,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
                $ = beginning of string
                \S* = any set of characters
                (?=\S{8,}) = of at least length 8
                (?=\S*[a-z]) = containing at least one lowercase letter
                (?=\S*[A-Z]) = and at least one uppercase letter
                (?=\S*[\d]) = and at least one number
                (?=\S*[\W]) = and at least a special character (non-word characters)
                $ = end of the string
        */
        if (!preg_match_all('$\S*(?=\S{12,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $candidate))
            return FALSE;
        return TRUE;
    }

    public static function generate_password($length = 20){
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789-=~!@#$%^&*_+?\|';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++)
            $str .= $chars[mt_rand(0, $max)];

        return $str;
    }

    public function postRegister(Request $request,Ajax $ajax){
        $rules = [
            'User_Confirm' => 'required',
            'User_FName' => 'required|min:3|max:50',
            'User_LName' => 'required',
            'User_Email' => 'required|email|unique:User_Detail,User_Email',
            'Password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6'
        ];

        $messages = [
            'User_Confirm.required' => 'User name is required.',
            'User_FName.required' => 'First name is required',
            'User_FName.min' => 'First name is min 3 characters',
            'User_FName.max' => 'First name is max 50 characters',
            'User_LName.required' => 'Last name is required',
            'User_Email.required' => 'Email is required',
            'User_Email.email' => 'Email is invalid format'

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use($request) {
            if (!empty($request->input('User_Confirm')) && (strlen($request->input('User_Confirm')) < 12 || strlen($request->input('User_Confirm')) > 30)) {
                $validator->errors()->add('User_Confirm', 'Username should be 12-30 characters');
            }
            if (!empty($request->input('Password')) && !$this->check_valid_pass($request->input('Password'), $request->input('User_Confirm'), $request->input('User_FName'), $request->input('User_LName'))) {
                $validator->errors()->add('Password', 'Password is not a valid');
            }
            if (!empty($request->input('Password')) && !$this->valid_pass($request->input('Password'))) {
                $validator->errors()->add('Password', 'Password is not a valid');
            }
        });

        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        $aDataAuth = DB::table('User_Authenticate')->where('User_Email',$request->input('User_Email'))->first(['User_Type','User_ID']);

        if($aDataAuth){
            $user = User::where('User_Login',md5($request->input('User_Confirm')))->orWhere('User_Email',$request->input('User_Email'))->first(['User_ID']);
            //echo '<pre>'; print_r($user); die;
            if(!$user){
                $tokenNumber = mt_rand();
                $convertedUsername = md5($request->input('User_Confirm'));
                $convertedPassword = md5($request->input('Password'));
                $dDate = date('Y-m-d H:i:s');
                $uUser_Type = $aDataAuth->User_Type;
                $iInitialF = substr($request->input('User_FName'), 0, 1);
                $iInitialL = substr($request->input('User_LName'), 0, 1);
                $uUserInitial = $iInitialF . $iInitialL;

                $user = new User();
                $user->User_ID = $aDataAuth->User_ID;
                $user->User_Login = $convertedUsername;
                $user->User_Confirm = $request->input('User_Confirm');
                $user->User_Type = $uUser_Type;
                $user->User_FName = $request->input('User_FName');
                $user->User_LName = $request->input('User_LName');
                $user->User_Email = $request->input('User_Email');
                $user->User_Intials = $uUserInitial;
                $user->Password = $convertedPassword;
                $user->Token_Number = $tokenNumber;
                $user->Is_Active = 1;
                $user->Is_Approve = 1;
                $user->Wrong_Login_Count = 0;
                $user->Last_Logged_In = $dDate;
                $user->User_Last_Logged_in = $dDate;
                //$user->RecordAddedDate = $dDate;
                $user->save();

                $subject = 'CRM Square Account Registration';
                $headers1 = "MIME-Version: 1.0" . "\r\n";
                $headers1 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers1 .= config('constant.CommonHeader');;
                $headers1 .= config('constant.CommonBcc');;

                $mMailMsgUser = '<!DOCTYPE HTML>' . '<head>' . '<meta http-equiv="content-type" content="text/html">' . '<title>Email notification</title>' . '</head>' . '<body>' . '<div id="outer" style="padding: 0px; background-color: white; width: 100%;margin: 0 auto;margin-top: 0px;">' . '<div id="inner" style="width: 100%;margin: 0 auto;font-family: Open Sans,Arial,sans-serif;font-size: 13px;font-weight: normal;line-height: 1.4em;color: #444;margin-top: 0px;">' . '<p>Dear ' . $request->input('User_FName') . ' ' . $request->input('User_LName') . ',</p><p>Congratulations.</p> <p>You have successfully registered.</p><p>Please store the URL, user name, and password in a safe place and whitelist CRM Square Administrator (admin@crmsquare.com).</p><p>Do not reply to this email. Please contact esupport@datasquare.com if you have any questions.</p><p>Thank you<br>Data Square Support Team<br>esupport@datasquare.com</p>' . '</div>' . '</div>' . '</body>';
                mail($request->input('User_Email'), $subject, $mMailMsgUser, $headers1);

                return $ajax->success()
                    ->appendParam('redirect',true)
                    ->redirectTo('login')
                    ->jscallback()
                    ->message('Congratulations! Registration was successful')
                    ->response();
            }else{
                return $ajax->fail()
                    ->message('You are already registered. Please go back to login screen')
                    ->jscallback()
                    ->response();
            }
        }else{
            return $ajax->fail()
                ->message('You are not authorized to register. Please contact your database administrator for pre-approval')
                ->jscallback()
                ->response();
        }
    }

    public function login(Request $request, Ajax $ajax){
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        $rules = [
            'User_Email' => 'required',
            'Password' => 'required'
        ];

        $messages = [
            'User_Email.required' => 'Email/Username is required',
            //'User_Email.email' => 'Email format is invaild',
            'Password.required' => 'Password is required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        $user = User::where(function ($subQry) use($request){
            $subQry->where('User_Confirm',$request->input('User_Email'))->orWhere('User_Email',$request->input('User_Email'));
        })->where('Password',md5($request->input('Password')))->first();
        //echo '<pre>'; print_r($user); die;
        if($user){
            /* Start Generate new token after every 24 hours */
            //echo date('Y-m-d h:i:s').'---'.$rowLogin['Last_Logged_In'];
            $tsSdate = date_create($user->Last_Logged_In); // format of yyyy-mm-dd
            $teEdate = date_create(date('Y-m-d H:i:s')); // format of yyyy-mm-dd

            $tDateDiff = date_diff($tsSdate, $teEdate);
            //echo '<pre>'; print_r($tDateDiff); die;
            /* End  here*/

            /* Expire password after exact 6 month */
            $psSdate = date_create($user->created_at); // format of yyyy-mm-dd
            $peEdate = date_create(date('Y-m-d H:i:s')); // format of yyyy-mm-dd
            $pDateDiff = date_diff($psSdate, $peEdate);
            /* End here*/

            if($user->Is_Active == 0){
                return $ajax->fail()
                    ->message("Your account has not yet been activated.It could take up to 48 hours for approval, verification, and activation.")
                    ->jscallback()
                    ->response();

            }else if($pDateDiff->m == 6 && ($user->Is_Active == 1)){
                $nNewPassword = self::generate_password(12);
                $nNewDate = date('Y-m-d H:i:s');

                $uUpdateUser = User::where(function ($subQry) use($request){
                    $subQry->where('User_Login',$request->input('User_Email'))->orWhere('User_Email',$request->input('User_Email'));
                })->first();
                $uUpdateUser->Password = md5($nNewPassword);
                $uUpdateUser->created_at = $nNewDate;
                $uUpdateUser->save();

                if($uUpdateUser->save()){
                    $to      = $uUpdateUser->User_Email;
                    $subject = 'Password Changed' ;
                    $message = 'New Password : '.$nNewPassword ;
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= config('constant.CommonHeader');
                    mail($to, $subject, $message, $headers);
                    return $ajax->fail()
                        ->message('You password has been expired, Please check the registered email for new password')
                        ->jscallback()
                        ->response();
                }

            }else if(($user->Password == md5($request->input('Password'))) && ($user->Wrong_Login_Count < 5) && ($user->Is_Active == 1)){
                if($user->Is_Approve == 0){ //die('IF');
                    $to       = $user->User_Email;
                    $subject  = 'CRM Square Account Access' ;
                    $tokenNumber = mt_rand();
                    $nNewDate = date('Y-m-d H:i:s');
                    /*$sqlUpdate = "UPDATE Users SET Token_Number='".$tokenNumber."',Is_Approve='0',User_Last_Logged_in='".$nNewDate."' WHERE User_Login='".$user->User_Login."'";
                    $aData=$oDb->executeSQL($sqlUpdate);
                    if($aData){
                        $message = '<!DOCTYPE HTML>'.'<head>'.'<meta http-equiv="content-type" content="text/html">'.'<title>Email notification</title>'.'</head>'.'<body>'.'<div id="outer" style="padding: 0px; background-color: white; width: 100%;margin: 0 auto;margin-top: 0px;">'. '<div id="inner" style="width: 100%;margin: 0 auto;font-family: Open Sans,Arial,sans-serif;font-size: 13px;font-weight: normal;line-height: 1.4em;color: #444;margin-top: 0px;">'.'<p>Dear '.$user->User_FName.' '.$user->User_LName.',</p><p>Your token number is <b>'.$tokenNumber.'</b>.</p> <p>Please note that this token will only be valid for 24 hours.</p><p>Do not reply to this email. Please contact esupport@datasquare.com if you have any questions.</p><p>Thank you<br>Data Square Support Team<br>esupport@datasquare.com</p>'.'</div>'.'</div>'.'</body>';
                        $headers  = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= 'To:'.$smail_to. "\r\n";
                        $headers .= config('constant.CommonHeader');
                        //$headers .= 'Cc:'.$smail_cc . "\r\n";
                        $headers .= 'Bcc:'.$smail_bcc. "\r\n";
                        mail($to, $subject, $message, $headers);
                        header("Location:lo_user_approval.php?unique_t=".$rowLogin['User_Login']);
                    }*/

                }else{

                    $uUserId = $user->User_ID;
                    $login_date = date('Y-m-d');

                    $cChkRecord= DB::table('User_Login_Rawdata')->whereDate('Login_Date',$login_date)->where('User_ID',$uUserId)->count();

                    if($cChkRecord == 0){
                        $values = array('Login_Date' => $login_date,'User_ID' => $uUserId);
                        DB::table('User_Login_Rawdata')->insert($values);
                    }

                    $pPartDate = explode(' ',$login_date);
                    $split_date = explode("-", $pPartDate[0]);
                    $year = $split_date[0];
                    $monthNum  = $split_date[1];
                    $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
                    $mMonthArr = array('January','February','March','April','May','June','July','August','September','October','November','December');

                    $aDataForYear = DB::table('User_Login_Info')->where('User_ID',$uUserId)->where('year',$year)->count();
                    $aDataForCount = DB::table('User_Login_Rawdata')->whereMonth('Login_Date',$monthNum)->whereYear('Login_Date',$year)->where('User_ID',$uUserId)->count();

                    if($aDataForYear == 0){
                        $fields = array();
                        $i = 0;
                        $fields['user_id'] = $uUserId;
                        foreach($mMonthArr as $mMnthName){
                            $i++;
                            if($mMnthName == $monthName){
                                $fields[$monthName] = $aDataForCount;
                            }else{
                                $fields[$mMnthName] = 0;
                            }
                        }
                        $fields['year'] = $year;
                        DB::table('User_Login_Info')->insert($fields);
                    }else{
                        DB::table('User_Login_Info')->where('user_id',$uUserId)->update([$monthName => $aDataForCount]);
                    }

                    //die;

                    //$auname = $user->User_Login;
                    //$userid = $rowLogin['User_Confirm'];
                    //$aemail = $rowLogin['User_Email'];

                    //$sVl = $rowLogin['User_Login']."^A".$rowLogin['User_Confirm']."^A".$userid."^A".$rowLogin['User_Type']."^A".$rowLogin['MgrID']."^A".$rowLogin['User_FName']."^A".$rowLogin['User_LName']."^A".$rowLogin['Sales_AssociateID'];
                    //$sql = "SELECT User_Type,User_JNPR_ID FROM Users_Authenticate WHERE User_Email = '".$aemail."'";

                    //$aDataAuth =$oDb->executeSelect($sql);
                    //echo '<pre>'; print_r($aDataAuth);
                    ///////$sVl = $rowLogin['User_Confirm']."^A".$rowLogin['User_Login']."^A".$userid."^A".$rowLogin['User_Type']."^A"."MgrID"."^A".$rowLogin['User_FName']."^A".$rowLogin['User_LName']."^A".$rowLogin['Sales_AssociateID']."^A".$aDataAuth[0]['User_JNPR_ID']."^A".$uUserId;  //"MHEMPE";
                    //die;
                    //	echo '<pre>'; print_r($sVl);	die('ENTER');
                    //$userid = $rowLogin['confirm_uname'];
                    //echo $sVl = $rowLogin['confirm_uname']."^A".$rowLogin['username']."^A".$userid; die;
                    //setcookie("DATASQUARE",$sVl);

                    DB::table('User_Detail')->where('User_Login',$user->User_Login)->update(['Last_Logged_in' => date('Y-m-d H:i:s')]);

                    //header("Location:index.php?m=".($rowLogin['User_Type'] == 'guest' ? 'ar' : 'dash&sm=dn'));
                   /* $aDataAuth = DB::table('User_Authenticate')->where('User_Email',$user->User_Email)->first(['User_Type','Visibilities']);
                    Session::put('User_Type', $aDataAuth->User_Type);
                    Session::put('Visibilities', $aDataAuth->Visibilities);*/
                   // echo '<pre>'; print_r(Session::get('User_Type')); die;

                    Auth::login($user, true);
                    return $ajax->success()
                        ->redirectTo(isset($user->authenticate->LandingPage) ? strtolower($user->authenticate->LandingPage) : 'lookup' )
                        ->jscallback()
                        ->response();
                }
            }else{
                if($user->Is_Active == 1){
                    $dDate= date('Y-m-d');
                    $dDateFromat = explode(' ',$user->Last_Logged_In); //echo '<pre>'; print_r($dDateFromat);die;
                    if($dDateFromat[0] == $dDate){
                        $user->Wrong_Login_Count = $user->Wrong_Login_Count+1;
                    }else{
                        $user->Wrong_Login_Count = 1;
                    }
                    if($user->Wrong_Login_Count >= 5){
                        $user->Wrong_Login_Count = 5;
                        $is_active = 0;
                        $msg = 'You have used wrong username and password more then five time, So now please contact with administrator for reactive your account';
                    }else{
                        $is_active = 1;
                        $msg = 'Invalid login details';
                    }
                    $nNewDateL = date('Y-m-d H:i:s');
                    DB::table('User_Detail')->where('User_Login',$user->User_Login)->update(['Is_Active',$is_active],['Wrong_Login_Count',1],['Last_Logged_In',$nNewDateL]);
                    return $ajax->fail()
                        ->message($msg)
                        ->jscallback()
                        ->response();
                }else{
                    return $ajax->fail()
                        ->message("You have used wrong username and password more then five time, So now please contact with administrator for reactive your account")
                        ->jscallback()
                        ->response();
                }
            }
        } else {
            return $ajax->fail()
                ->message('Invalid login details')
                ->jscallback()
                ->response();
        }
    }

    public function dlogin(Request $request, Ajax $ajax){
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        $rules = [
            'User_Email' => 'required',
            'Password' => 'required'
        ];

        $messages = [
            'User_Email.required' => 'Username is required',
            //'User_Email.email' => 'Email format is invaild',
            'Password.required' => 'Password is required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        if($request->User_Email == 'dashboard2021' && $request->Password == 'dev'){
            Session::put('dummyuserlogin',true);
            $backUrl = Session::get('backUrl');
            return $ajax->success()
                ->redirectTo($backUrl)
                ->jscallback()
                ->response();
        } else {
            return $ajax->fail()
                ->message('Invalid login details')
                ->jscallback()
                ->response();
        }
    }

    public function allUsersHistory(Ajax $ajax){
        $qry = DB::select('SELECT uli.*,u.User_FName,User_LName FROM User_Login_Info uli, User_Detail u WHERE u.User_ID = uli.User_ID');
        $histories = collect($qry)->map(function($x){ return (array) $x; })->toArray();
        $content = View::make('users.allloginhistory',['histories' => $histories])->render();

        $sdata = [
            'content' => $content
        ];

        $title = 'All Users Login History';
        $size = 'modal-dialog-centered modal-xl';

        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.modal-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()
            ->appendParam('html', $html)
            ->jscallback('loadModalLayout')
            ->response();
    }

    public function forgetPassword(Request $request, Ajax $ajax){
        $rules = [
            'User_Confirm' => 'required',
        ];

        $messages = [
            'User_Confirm.required' => 'Username is required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        $auname = $request->input('User_Confirm');
        $sql = DB::select("SELECT User_ID,Is_Approve,Token_Number,User_Email,User_Login,User_Confirm,Password,Wrong_Login_Count,Is_Active,Last_Logged_In,User_FName,User_LName FROM User_Detail WHERE User_Confirm = '" . $auname . "'");
        $rowLogin = collect($sql)->map(function($x){ return (array) $x; })->toArray();
        //echo '<pre>'; print_r($rowLogin); die;
        $cCnt = count($rowLogin);
        if ($cCnt > 0) {
            $rowLogin = $rowLogin[0];
            if (($rowLogin['Wrong_Login_Count'] < 5) && ($rowLogin['Is_Active'] == 1)) {
                $nNewPassword = self::generate_password(12);
                //echo $nNewPassword;
                DB::update("UPDATE User_Detail SET Password='" . md5($nNewPassword) . "' WHERE User_Confirm = '" . $auname . "'");

                try{
                    $to = $rowLogin['User_Email'];
                    $subject = 'Password Changed' ;
                    $message = 'New Password : '.$nNewPassword ;
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= config('constant.CommonHeader');
                    mail($to, $subject, $message, $headers);
                    return $ajax->success()
                        ->message('New password generated successfully, Please check registered email')
                        ->appendParam('User_Confirm',$auname)
                        ->jscallback('ajax_forgetpassword')
                        ->response();

                }catch (\Exception $exception){
                    return $ajax->fail()
                        ->message($exception->getMessage())
                        ->jscallback()
                        ->response();
                }
            } else {
                return $ajax->success()
                    ->message('Your account is deactivated, Please contact with Administrator to reactivate your account')
                    ->jscallback()
                    ->response();
            }
        } else {
            return $ajax->fail()->message('Username doesn\'t exist')->jscallback()->response();
        }
    }

    public function changePassword(Request $request, Ajax $ajax){
        $rules = [
            'old_password' => 'required',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6'
        ];

        $messages = [
            'old_password.required' => 'Old password is required.',
            'password.required' => 'New password is required.',
            'confirm_password.required' => 'Confirm password is required',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use($request) {

            if (!empty($request->input('password')) && !$this->check_valid_pass($request->input('password'), Auth::user()->User_Confirm, Auth::user()->User_FName, Auth::user()->User_LName)) {
                $validator->errors()->add('password', 'Password is not a valid');
            }
            if (!empty($request->input('Password')) && !$this->valid_pass($request->input('password'))) {
                $validator->errors()->add('Password', 'Password is not a valid');
            }
        });

        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        $rowData = User::where(function ($subQry) use($request){
            $subQry->where('User_Login',md5(Auth::user()->User_Confirm))->where('Password',md5($request->input('old_password')));
        })->first();

        if($rowData){
            if($rowData->Password != md5($request->input('old_password'))){
                return $ajax->fail()
                    ->jscallback()
                    ->message('Old password is invalid')
                    ->response();
            }else{
                $convertedPassword = md5($request->input('password'));
                DB::update("UPDATE User_Detail SET Password = '$convertedPassword' WHERE User_ID='".$rowData->User_ID."'");
                return $ajax->success()
                    ->jscallback('ajax_user_pwdCng')
                    ->message('Password changed successfully')
                    ->response();
            }
        }else{
            return $ajax->fail()
                ->jscallback()
                ->message('Wrong username & Password')
                ->response();
        }
    }
}
