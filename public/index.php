<?php
require_once __DIR__ . '/../vendor/autoload.php';
//INCLUDING APP SETTINGS
require_once __DIR__ . '/../app/config/settings.php';
//INCLUDING COMMON FUNCTIONS
require_once __DIR__ . '/../app/includes/common.php';
//INCLUDING APP CORE
require_once __DIR__ . '/../app/classes/core.php';
//INCLUDING ROUTES
require_once __DIR__ . "/../app/config/routes.php";
require_once __DIR__ . '/../app/classes/middleware.php';


$core = core::getInstance($routes);
$core->setUpSessions();
$core->mountApp();
