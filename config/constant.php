<?php

$repo = 'DB_CRMLV_v16D_AB';
return [
    'BaseUrl' => 'https://crmsquare.com/'.$repo.'/',
    'schDir' => $repo,
    'filePath' => 'D:\sftpuser\Miscsftp1\\'.$repo.'\\',
    'phpPath' => 'C:\\Program Files (X86)\\PHP\\v7.2\\php.exe',
    'title' => 'CRM',
    'loader_label' => 'CRM',
    'top_left_logo1' => 'img/logo.gif',
    'top_left_logo2' => 'img/logo1.jpg',
    'top_nav_title' => 'The Zen Studies Society, Inc.',
    'help_pdf' => 'help/CRM Square User Guide v5.4.pdf',
    'top_right_logo' => 'img/crmlogo.png',
    'footer_label' => 'Copyright &copy;  2007 -  '.date("Y").' Data Square. All Rights Reserved.',
    'creator' => 'Data Square',
    'client_name' => 'ZSS',
    'record_per_page' => 15,
    'hostUrl' => 'https://crmsquare.com/',
	'schtasks_dir' => 'schtasks_v16D_AB',
	'schtasks_dir_path' => 'D:\sftpuser\Miscsftp1\schtasks_v16D_AB\\',
    'curlcert' => 'C:\Program Files (x86)\IIS Express\PHP\v7.2\cacert.pem',
    'prefix' => 'Dev_',

    'CommonHeader' => 'From: CRM Square Administrator<admin@crmsquare.com>' . "\r\n",
    'CommonBcc' => 'Bcc: devyani@datasquare.com' . "\n",
    'CommonSupervisor' => 'devyani@datasquare.com',
    'MaintenanceMode' => 0,

    'is_right_click_enabled' => 1,
    'is_f12_key_enabled' => 1,
    'is_Ctrl_plus_u_shortcut_enabled' => 1,
    'is_Ctrl_plus_shift_plus_u_shortcut_enabled' => 1,

    'XlsxHeaderCells' => ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CX','CY','CZ','DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DX','DY','DZ'],
    'permissions' => [
        'modules' => [
            'settings' => [
                'show_as' => 'Settings',
                'rights' => ['add' => 0,'view' => 1, 'edit' => 0, 'trash' => 0],
                'submodules' => [
                    'users' => [
                        'show_as' => 'Users',
                        'rights' => ['add' => 0,'view' => 1, 'edit' => 0, 'trash' => 0],
                        'submodules' => []
                    ],
                    'profile_master' => [
                        'show_as' => 'Profiles',
                        'rights' => ['add' => 0,'view' => 1, 'edit' => 0, 'trash' => 0],
                        'submodules' => []
                    ]
                ]
            ]
        ]
    ]
];
