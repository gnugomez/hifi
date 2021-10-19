<?php
require __DIR__ . '/vendor/autoload.php';
//INCLUDING APP SETTINGS
require_once __DIR__ . '/settings.php';
//INCLUDING COMMON FUNCTIONS
require_once __DIR__ . '/common.php';
//INCLUDING API CALLBACKS
require_once __DIR__ . '/classes/content.php';
//INCLUDING APP CORE
require_once __DIR__ . '/classes/core.php';
//INCLUDING ROUTES
require_once __DIR__ . "/routes.php";
require_once __DIR__ . '/classes/middleware.php';

$core = core::getInstance($routes);
$core->setUpSessions();
$core->mountApp();
