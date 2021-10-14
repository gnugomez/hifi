<?php
require __DIR__ . '/vendor/autoload.php';

//INCLUDING APP CORE
require_once __DIR__ . '/core.php';
//INCLUDING APP TEMPLATES
$views = glob(__DIR__ . '/views/controllers/*.php');
foreach ($views as $view) {
    require_once $view;
}
//INCLUDING ROUTER
require_once __DIR__ . '/router.php';
//INCLUDING APP SETTINGS
require_once __DIR__ . '/settings.php';
//INCLUDING DB CONNECTION
require_once __DIR__ . '/dbcon.php';
