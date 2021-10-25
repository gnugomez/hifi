<?php
require_once __DIR__ . '/../vendor/autoload.php';
//INCLUDING APP SETTINGS
require_once __DIR__ . '/../app/shared/config/settings.php';
//INCLUDING COMMON FUNCTIONS
require_once __DIR__ . '/../app/shared/includes/common.php';

importFromDir(__DIR__ . "/../app/shared/classes");
importFromDir(__DIR__ . "/../app/shared/services");
