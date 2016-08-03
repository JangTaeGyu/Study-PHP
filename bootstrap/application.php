<?php

use App\Core\{ Session, Redirect };

require_once __DIR__ . '/../app/init.php';

$app = new App\Core\Application;

$app->all('', \App\Controllers\LoginController::class, 'login')->auth(false)->title('로그인');
$app->all('/signup', \App\Controllers\SignUpController::class, 'signup')->auth(false)->title('회원가입');

$app->get('/main', \App\Controllers\MainController::class, 'main')->auth(true)->title('메인');
$app->all('/update', \App\Controllers\UpdateController::class, 'update')->auth(true)->title('정보수정');
$app->all('/password_change', \App\Controllers\PasswordChangeController::class, 'passwordChange')->auth(true)->title('비밀번호변경');

$app->get('/menu/task', \App\Controllers\TaskController::class, 'task')->auth(true)->title('업무');
$app->get('/menu/task/waiting', \App\Controllers\TaskController::class, 'waiting')->auth(true)->title('업무');
$app->get('/menu/task/complete', \App\Controllers\TaskController::class, 'complete')->auth(true)->title('업무');
$app->get('/menu/task/issue', \App\Controllers\TaskController::class, 'issue')->auth(true)->title('업무');
$app->get('/menu/task/input', \App\Controllers\TaskController::class, 'input')->auth(true)->title('업무');
$app->post('/menu/task/create', \App\Controllers\TaskController::class, 'create')->auth(true)->title('업무생성');
$app->post('/menu/task/update', \App\Controllers\TaskController::class, 'update')->auth(true)->title('업무수정');

$app->get('/menu/member', \App\Controllers\MemberController::class, 'member')->auth(true)->title('회원');
$app->post('/menu/member/create', \App\Controllers\MemberController::class, 'create')->auth(true)->title('회원생성');
$app->post('/menu/member/update', \App\Controllers\MemberController::class, 'update')->auth(true)->title('회원수정');
$app->post('/menu/member/delete', \App\Controllers\MemberController::class, 'delete')->auth(true)->title('회원삭제');

$app->get('/menu/code', \App\Controllers\CodeController::class, 'code')->auth(true)->title('코드');
$app->post('/menu/code/create', \App\Controllers\CodeController::class, 'create')->auth(true)->title('코드생성');
$app->post('/menu/code/update', \App\Controllers\CodeController::class, 'update')->auth(true)->title('코드수정');
$app->post('/menu/code/delete', \App\Controllers\CodeController::class, 'delete')->auth(true)->title('코드삭제');

$app->get('/logout', function(){
    Session::destroy();
    Redirect::to('/');
})->auth(true);


$app->run();