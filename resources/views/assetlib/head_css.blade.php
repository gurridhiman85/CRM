<?php
$css_files = [
    'sweetalert' => [
        'path' => '/assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
        'required' => 1
    ],

    'nprogress' => [
        'path' => '/assets/bower_components/nprogress/nprogress.css',
        'required' => 1
    ],



];

\App\Library\AssetLib::echoCssFiles($css_files);
