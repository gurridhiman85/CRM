<?php
if(isset($data)){
    $userACtiveTheme = \App\Model\UserMeta::where('u_dataid',Auth::user()->u_dataid)->where('meta_key','ACTIVE_THEME')->first();
    $default = true;
    $themes = [
        [
            'data-skin' => 'skin-default',
            'class' => 'default-theme'
        ],
        [
            'data-skin' => 'skin-green',
            'class' => 'green-theme'
        ],
        [
            'data-skin' => 'skin-red',
            'class' => 'red-theme'
        ],
        [
            'data-skin' => 'skin-blue',
            'class' => 'blue-theme'
        ],
        [
            'data-skin' => 'skin-purple',
            'class' => 'purple-theme'
        ],
        [
            'data-skin' => 'skin-megna',
            'class' => 'megna-theme'
        ],
    ];

    $mytheme = 'default-theme';
    if($userACtiveTheme){
        $mytheme = $userACtiveTheme->meta_value;
        $default = false;
    }


?>

    <div class="slimscrollright">
        <div class="rpanel-title"> Additional Settings <span><i class="ti-close right-side-toggle"></i></span> </div>
        <div class="r-panel-body">
            <ul id="themecolors" class="m-t-20">
                <li><b>With Light sidebar</b></li>

                @foreach($themes as $theme)

                    <li class="ajax_theme"><a href="javascript:void(0)" data-skin="{{$theme['data-skin']}}" class="{{$theme['class']}} @if($theme['data-skin'] == $mytheme) working @endif">1</a></li>

                @endforeach

            </ul>
        </div>
    </div>


<script type="application/javascript">
    $(document).ready(function () {
        $('.ajax_theme').on('click',function () {
            var aTheme = $(this).children('a').data('skin');
            $('#themecolors li a').removeClass('working');
            $(this).children('a').addClass('working');
            $('body').attr('class','fixed-layout ' + aTheme);

            ACFn.sendAjax(
                '/user/metadata',
                'POST',
                {
                    _token : '{{csrf_token()}}',
                    u_dataid : '{{Auth::user()->u_dataid}}',
                    meta_key : 'ACTIVE_THEME',
                    meta_value : aTheme,
                }
            )
        })
    })
</script>

<?php
} else {
?>
<div class="right-sidebar"></div>
<?php
}
?>