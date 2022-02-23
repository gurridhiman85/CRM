<?php


$js_files = [
    'jquery' => [
        'path' => 'assets/node_modules/jquery/jquery-3.2.1.min.js',
        'required' => 1
    ]
];


\App\Library\AssetLib::echoJsFiles($js_files);