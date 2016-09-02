<?php

use App\Core\{ Session, Redirect };

require_once __DIR__ . '/../app/init.php';

$app = new App\Core\Application;

$app->all('', \App\Controllers\LoginController::class, 'allLogin')->auth(false)->title('로그인');
$app->all('/signup', \App\Controllers\SignUpController::class, 'allSignup')->auth(false)->title('회원가입');

$app->get('/main', \App\Controllers\MainController::class, 'getMain')->auth(true)->title('메인');
$app->all('/update', \App\Controllers\UpdateController::class, 'allUpdate')->auth(true)->title('정보수정');
$app->all('/password_change', \App\Controllers\PasswordChangeController::class, 'allPasswordChange')->auth(true)->title('비밀번호변경');

$app->get('/menu/task', \App\Controllers\TaskController::class, 'getTask')->auth(true)->title('업무');
$app->get('/menu/task/waiting', \App\Controllers\TaskController::class, 'getWaiting')->auth(true)->title('업무');
$app->get('/menu/task/complete', \App\Controllers\TaskController::class, 'getComplete')->auth(true)->title('업무');
$app->get('/menu/task/issue', \App\Controllers\TaskController::class, 'getIssue')->auth(true)->title('업무');
$app->get('/menu/task/input', \App\Controllers\TaskController::class, 'getInput')->auth(true)->title('업무');
$app->post('/menu/task/create', \App\Controllers\TaskController::class, 'postCreate')->auth(true)->title('업무생성');
$app->post('/menu/task/update', \App\Controllers\TaskController::class, 'postUpdate')->auth(true)->title('업무수정');

$app->get('/menu/member', \App\Controllers\MemberController::class, 'getMember')->auth(true)->title('회원');
$app->post('/menu/member/create', \App\Controllers\MemberController::class, 'postCreate')->auth(true)->title('회원생성');
$app->post('/menu/member/update', \App\Controllers\MemberController::class, 'postUpdate')->auth(true)->title('회원수정');
$app->post('/menu/member/delete', \App\Controllers\MemberController::class, 'postDelete')->auth(true)->title('회원삭제');

$app->get('/menu/code', \App\Controllers\CodeController::class, 'getCode')->auth(true)->title('코드');
$app->post('/menu/code/create', \App\Controllers\CodeController::class, 'postCreate')->auth(true)->title('코드생성');
$app->post('/menu/code/update', \App\Controllers\CodeController::class, 'postUpdate')->auth(true)->title('코드수정');
$app->post('/menu/code/delete', \App\Controllers\CodeController::class, 'postDelete')->auth(true)->title('코드삭제');

$app->get('/logout', function(){
    Session::destroy();
    Redirect::to('/');
})->auth(true);

$app->run();