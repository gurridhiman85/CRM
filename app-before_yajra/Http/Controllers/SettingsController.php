<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Library\Ajax;
use App\Library\AjaxSideLayout;
use App\Model\Department;
use App\Model\Designation;
use App\Model\Permissions;
use App\Model\Profile;
use App\Model\ProfileMaster;
use App\Model\Setting;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Crypt;

use \Illuminate\Support\Facades\View as View;

class SettingsController extends Controller
{
    public function index(Ajax $ajax){
        if(!Helper::CheckPermission(null,'settings','view')){
            $view = View::make('layouts.error_pages.404');
        }else{
            $view = View::make('settings.index')->render();
        }
        return $ajax->success()
            ->jscallback('ajax_load_content')
            ->appendParam('html',$view)
            ->message('')
            ->response();
    }

    public function getSettings(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $page = $request->input('page',1);
        $record_per_page = config('constant.record_per_page');
        $start_from = ($page-1) * $record_per_page;
        if($tabid == Setting::USERS){
            $users = User::skip($start_from)
                ->take($record_per_page)
                ->get();
            $total_users = User::count();

            $html = View::make('settings.users.table',['users' => $users,'total_users' => $total_users])->render();
            return $ajax->success()
                ->appendParam('html',$html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        elseif ($tabid == Setting::PROFILE_MASTER){

            $profiles = Profile::skip($start_from)
                ->take($record_per_page)
                ->get();
            $total_profiles = Profile::count();

            $html = View::make('settings.profile_master.table',['profiles' => $profiles,'total_profiles' => $total_profiles])->render();
            return $ajax->success()
                ->appendParam('html',$html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        elseif ($tabid == Setting::DEPARTMENT){

            $departments = Department::skip($start_from)
                ->take($record_per_page)
                ->get();
            $total_departments = Department::count();

            $html = View::make('settings.departments.table',['departments' => $departments,'total_departments' => $total_departments])->render();
            return $ajax->success()
                ->appendParam('html',$html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        elseif ($tabid == Setting::DESIGNATION){

            $designations = Designation::skip($start_from)
                ->take($record_per_page)
                ->get();
            $total_designations = Designation::count();

            $html = View::make('settings.designations.table',['designations' => $designations,'total_designations' => $total_designations])->render();
            return $ajax->success()
                ->appendParam('html',$html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
    }

    public function addUser($end_uid,Ajax $ajax){
        $uid = Crypt::decrypt($end_uid);
        $profiles = Profile::where('is_active',1)
            ->get();

        $title = ($uid == '0') ? 'Add User' : 'Edit User';
        $user = array();
        if($uid != '0'){
            $user = User::where('u_dataid',$uid)->first();
        }
        $content = View::make('settings.forms.adduser',['profiles' => $profiles,'user' => $user])->render();

        $sdata = [
            'content' => $content
        ];
        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.side-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()->appendParam('html',$html)->jscallback('loadSideLayout')->response();
    }

    public function postUser(Request $request, Ajax $ajax){
        $userId = $request->input('id') != '0' ? ','.$request->input('id') : '';
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email'.$userId,
            'profile_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }

        if($request->input('u_dataid') != '0'){
            $user = User::where('u_dataid',$request->input('u_dataid'))->first();
            $ajax->message('User Updated Successfully');
        }else{
            $user = new User();
            $user->u_dataid = 'U'.time();

            $user->password = bcrypt(123456);
            $ajax->message('User Created Successfully');
        }
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->profile_id = $request->profile_id;
        $user->is_active = $request->is_active ? $request->is_active : 0;;
        $user->save();

        return $ajax->success()
            ->jscallback('ajax_profile_load')
            ->reload_page()
            ->response();
    }

    public function addProfile($enc_pid, Ajax $ajax){
        $pid = Crypt::decrypt($enc_pid);
        $title = ($pid == '0') ? 'Add Profile' : 'Edit Profile';
        $profile = array();

        if($pid != '0'){
            $profile = Profile::where('profile_id',$pid)->first();

        }

        $content = View::make('settings.forms.addprofile',['profile' => $profile])->render();

        $sdata = [
            'content' => $content
        ];
        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.side-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()->appendParam('html',$html)->jscallback('loadSideLayout')->response();
    }

    public function postProfile(Request $request, Ajax $ajax){
        $profileId = $request->input('id') != '0' ? ','.$request->input('id') : '';
        $rules = [
            'profile_name' => 'required|unique:profiles,profile_name'.$profileId
        ];

        $messages = [
            'profile_name.required' => 'Profile name is required',
            'profile_name.unique' => 'Profile name already in use'

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }


        if($request->input('profile_id') != '0'){
            $profile = Profile::where('profile_id',$request->input('profile_id'))->first();
            $ajax->message('Profile Updated Successfully');
        }else{
            $profile = new Profile();
            $profile->profile_id = 'P'.time();
            $ajax->message('Profile Created Successfully');
        }

        $profile->profile_name = $request->profile_name;
        $profile->is_active = $request->is_active ? $request->is_active : 0;
        $profile->save();

        return $ajax->success()
            ->jscallback('ajax_profile_load')
            ->reload_page()
            ->message('New Profile Created Successfully')
            ->response();
    }

    public function changeProfileStatus($enc_pid,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $pid = Crypt::decrypt($enc_pid);
        $profile = Profile::where('profile_id',$pid)->first();
        $sStatusToggle = $profile->is_active == 1 ? 0 : 1;
        if(!$profile){
            return $ajax->fail()
                ->message('Profile not found !')
                ->jscallback()
                ->response();
        }
        Profile::where('profile_id',$pid)->update(['is_active' => $sStatusToggle]);
        $statusText  = $profile->is_active == 1 ? 'InActive' : 'Active';
        return $ajax->success()
            ->message('Profile '.$statusText)
            ->appendParam('is_active', $sStatusToggle)
            ->jscallback('ajax_status_toggle')
            ->response();
    }

    public function changeUserStatus($enc_uid,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $uid = Crypt::decrypt($enc_uid);
        $user = User::where('u_dataid',$uid)->first();
        $sStatusToggle = $user->is_active == 1 ? 0 : 1;
        if(!$user){
            return $ajax->fail()
                ->message('Profile not found !')
                ->jscallback()
                ->response();
        }
        User::where('u_dataid',$uid)->update(['is_active' => $sStatusToggle]);
        $statusText  = $user->is_active == 1 ? 'InActive' : 'Active';
        return $ajax->success()
            ->message('User '.$statusText)
            ->appendParam('is_active', $sStatusToggle)
            ->jscallback('ajax_status_toggle')
            ->response();
    }

    public function trashUser($enc_uid,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $uid = Crypt::decrypt($enc_uid);
        $user = User::where('u_dataid',$uid)->first();
        if(!$user){
            return $ajax->fail()
                ->message('User not found !')
                ->jscallback()
                ->response();
        }
        User::where('u_dataid',$uid)->delete();
       return $ajax->success()
            ->message('User Trashed')
            ->jscallback('ajax_profile_load')
            ->response();
    }

    public function trashProfile($enc_pid,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $pid = Crypt::decrypt($enc_pid);
        $profile = Profile::where('profile_id',$pid)->first();
        if(!$profile){
            return $ajax->fail()
                ->message('Profile not found !')
                ->jscallback()
                ->response();
        }
        Profile::where('profile_id',$pid)->delete();
         return $ajax->success()
            ->message('Profile Trashed')
            ->jscallback('ajax_profile_load')
            ->response();
    }


    public function profilePermissions($enc_pid, Ajax $ajax){
        $pid = Crypt::decrypt($enc_pid);
        $title = 'Profile Permissions';
        $DBpermissions = Permissions::where('profile_id',$pid)->get();
        $content = View::make('settings.forms.profile-permissions',['DBpermissions' => $DBpermissions,'profileid'=>$pid])->render();
        $sdata = [
            'content' => $content
        ];
        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.side-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()->appendParam('html',$html)->jscallback('loadSideLayout')->response();
    }

    public function updatePermissions(Request $request,Ajax $ajax){
        $profile_id = $request->input('profile_id');
        Permissions::where('profile_id',$profile_id)->delete();
        $permissions = $request->input('permissions');
        foreach ($permissions as $module=>$permission){
            foreach ($permission['rights'] as $rightname=>$right){
                $pr = new Permissions();
                $pr->permission_id = time().mt_rand(10,1000);
                $pr->profile_id = $profile_id;
                $pr->module = $module;
                $pr->rights_name = $rightname;
                $pr->is_rights = $right;
                $pr->parents = $permission['parents'];
                $pr->save();
            }
        }

        return $ajax->success()
            ->message('Profile Trashed')
            ->jscallback('ajax_profile_load')
            ->response();

    }



    public function addDepartment($enc_did, Ajax $ajax){
        $did = Crypt::decrypt($enc_did);
        $title = ($did == '0') ? 'Add Department' : 'Edit Department';
        $department = array();

        if($did != '0'){
            $department = Department::where('id',$did)->first();

        }

        $content = View::make('settings.forms.adddepartment',['department' => $department])->render();

        $sdata = [
            'content' => $content
        ];
        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.side-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()->appendParam('html',$html)->jscallback('loadSideLayout')->response();
    }

    public function postDepartment(Request $request, Ajax $ajax){
        $departmentId = $request->input('id') != '0' ? ','.$request->input('id') : '';
        $rules = [
            'department_name' => 'required|unique:departments,department_name'.$departmentId
        ];

        $messages = [
            'department_name.required' => 'Department name is required',
            'department_name.unique' => 'Department name already in use'

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }


        if($request->input('id') != '0'){
            $department = Profile::where('id',$request->input('id'))->first();
            $ajax->message('Department Updated Successfully');
        }else{
            $department = new Department();
            $department->id = time();
            $ajax->message('Department Created Successfully');
        }

        $department->department_name = $request->department_name;
        $department->status = $request->status ? $request->status : 0;
        $department->save();

        return $ajax->success()
            ->jscallback('ajax_profile_load')
            ->reload_page()
            ->message('New Department Created Successfully')
            ->response();
    }

    public function changeDepartmentStatus($enc_did,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $did = Crypt::decrypt($enc_did);
        $department = Department::where('id',$did)->first();
        $sStatusToggle = $department->status == 1 ? 0 : 1;
        if(!$department){
            return $ajax->fail()
                ->message('Department not found !')
                ->jscallback()
                ->response();
        }
        Department::where('id',$did)->update(['status' => $sStatusToggle]);
        $statusText  = $department->status == 1 ? 'InActive' : 'Active';
        return $ajax->success()
            ->message('Department '.$statusText)
            ->appendParam('is_active', $sStatusToggle)
            ->jscallback('ajax_status_toggle')
            ->response();
    }

    public function trashDepartment($enc_did,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $did = Crypt::decrypt($enc_did);
        $department = Department::where('id',$did)->first();
        if(!$department){
            return $ajax->fail()
                ->message('Department not found !')
                ->jscallback()
                ->response();
        }
        Department::where('profile_id',$did)->delete();
        return $ajax->success()
            ->message('Department Trashed')
            ->jscallback('ajax_profile_load')
            ->response();
    }


    public function addDesignation($enc_did, Ajax $ajax){
        $did = Crypt::decrypt($enc_did);
        $title = ($did == '0') ? 'Add Designation' : 'Edit Designation';
        $designation = array();

        if($did != '0'){
            $designation = Designation::where('id',$did)->first();

        }

        $content = View::make('settings.forms.adddesignation',['designation' => $designation])->render();

        $sdata = [
            'content' => $content
        ];
        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.side-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()->appendParam('html',$html)->jscallback('loadSideLayout')->response();
    }

    public function postDesignation(Request $request, Ajax $ajax){
        $designationId = $request->input('id') != '0' ? ','.$request->input('id') : '';
        $rules = [
            'designation_name' => 'required|unique:designations,designation_name'.$designationId
        ];

        $messages = [
            'designation_name.required' => 'Designation name is required',
            'designation_name.unique' => 'Designation name already in use'

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }


        if($request->input('id') != '0'){
            $designation = Designation::where('id',$request->input('id'))->first();
            $ajax->message('Designation Updated Successfully');
        }else{
            $designation = new Designation();
            $designation->id = time();
            $ajax->message('Designation Created Successfully');
        }

        $designation->designation_name = $request->designation_name;
        $designation->status = $request->status ? $request->status : 0;
        $designation->save();

        return $ajax->success()
            ->jscallback('ajax_profile_load')
            ->reload_page()
            ->message('New Designation Created Successfully')
            ->response();
    }

    public function changeDesignationStatus($enc_did,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $did = Crypt::decrypt($enc_did);
        $designation = Designation::where('id',$did)->first();
        $sStatusToggle = $designation->status == 1 ? 0 : 1;
        if(!$designation){
            return $ajax->fail()
                ->message('Designation not found !')
                ->jscallback()
                ->response();
        }
        Designation::where('id',$did)->update(['status' => $sStatusToggle]);
        $statusText  = $designation->status == 1 ? 'InActive' : 'Active';
        return $ajax->success()
            ->message('Designation '.$statusText)
            ->appendParam('is_active', $sStatusToggle)
            ->jscallback('ajax_status_toggle')
            ->response();
    }

    public function trashDesignation($enc_did,Request $request,Ajax $ajax){
        if(!Auth::user()->IsSuperAdmin){
            return view('layouts.error_pages.404');
        }

        $did = Crypt::decrypt($enc_did);
        $designation = Designation::where('id',$did)->first();
        if(!$designation){
            return $ajax->fail()
                ->message('Designation not found !')
                ->jscallback()
                ->response();
        }
        Designation::where('id',$did)->delete();
        return $ajax->success()
            ->message('Designation Trashed')
            ->jscallback('ajax_profile_load')
            ->response();
    }
}
