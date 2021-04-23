$(document).ready(function () {
    ACFn.ajax_status_toggle = function (F,R) {
        console.log(F)
        if(R.is_active){
            F.children('i').addClass('fa-check').removeClass('fa-times');

            //F.addClass('btn-success').removeClass('btn-danger');

        }else{

            F.children('i').addClass('fa-times').removeClass('fa-check')
           // F.addClass('btn-danger').removeClass('btn-success');
        }
    }
    ACFn.ajax_profile_load = function (F,R) {
        if(R.success){

            $('.tab-hash li a.active').trigger('show.bs.tab');
            $('.bk-overlay .modal-header .side-close').trigger('click');
        }
    }

    ACFn.ajax_update_image = function (F , R) {
        $('.pp_img').attr('src',R.u_img_pth)
        $('.pp').hide();
        ACFn.display_message(R.messageTitle,'','success');
    }
    
    ACFn.ajax_load_content = function (F , R) {
        if(R.success){
            $('.page-wrapper').html(R.html)
        }
    }

    ACFn.ajax_load_rightpanel = function (F , R) {
        if(R.success){
            F.html(R.html)
        }
    }
})