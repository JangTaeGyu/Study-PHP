<?php

use App\Models\User\UserSessions;

define("PATH_ROOT", dirname(__FILE__));

define("PATH_ROOT_PUBLIC", PATH_ROOT . "/../public");

define("PATH_ROOT_VIEWS", PATH_ROOT . "/../resources/views");

//session_set_cookie_params(0, '/', '.domain.co.kr');

session_start();

require_once 'psr4.php';

$GLOBALS['config'] = require_once 'Config/Config.php';

$GLOBALS['container'] = [
    'csrf' => new \App\Core\Csrf,
];

require_once 'Functions/Array.php';
require_once 'Functions/Connection.php';
require_once 'Functions/View.php';

// 세션
$session = UserSessions::getInstance()->getSessionLimit();
if ($session->count() > 0) {
    $GLOBALS['session'] = $session->first();
} else {
	$GLOBALS['session'] = null;
}