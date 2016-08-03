<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User\User;
use App\Core\{ Hash, Input, Session, Redirect, Validator };

class SignUpController extends Controller
{
    public function signup(array $request, array $response, bool $exists)
    {
        if ($exists) {
            try {
                // Token 체크하기
                if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

                // 유효성 검사
                $validation = new Validator($request);
                $validation->check('id', '아이디')->required()->min(4)->max(12)->isUserId();
                $validation->check('password', '비밀번호')->required()->min(4)->max(12);
                $validation->check('password_confirm', '비밀번호확인')->required()->matches('password');
                $validation->check('name', '이름')->required()->min(2)->max(12);
                $validation->check('email', '이메일')->required()->email();
                if (!$validation->passed()) {
                    $response['errors'] = $validation->errors();
                    throw new \Exception("파라미터 유효성 검증에 실패하였습니다.");
                }

                // Hash Key 생성
                $salt = Hash::salt(32);

                // 회원저장
                $result = User::getInstance()->create([
                    'id' => $request['id'],
                    'password' => Hash::make($request['password'], $salt),
                    'salt' => $salt,
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'state' => 'W',
                    'reg_date' => date('Y-m-d H:i:s')
                ]);
                if (!$result) throw new \Exception("회원가입에 실패하였습니다.");

                Session::flash('success', '회원가입에 성공하셨습니다.');

                Redirect::to('/');

            } catch (\Exception $e) {
                $response['result'] = false;
                $response['message'] = $e->getMessage();
            }
        }

        view('signup', ['input' => $request, 'output' => $response]);
    }
}