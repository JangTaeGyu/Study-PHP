<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User\User;
use App\Core\{ Hash, Input, Session, Redirect, Validator };

class LoginController extends Controller
{
    public function allLogin(array $request, array $response, bool $exists)
    {
        if ($exists) {
            try {
                // Token 체크하기
                if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

                // 유효성 검사
                $validation = new Validator($request);
                $validation->check('id', '아이디')->required()->min(4)->max(12);
                $validation->check('password', '비밀번호')->required()->min(4)->max(12);
                if (!$validation->passed()) {
                    $response['errors'] = $validation->errors();
                    throw new \Exception("파라미터 유효성 검증에 실패하였습니다.");
                }

                // 회원가입 유무 체크
                $user = User::getInstance()->read($request['id']);
                if ($user->count() === 0) throw new \Exception("일치하는 아이디가 없습니다.");

                // 비밀번호 체크
                if (!password_verify($request['password'], $user->first()['password'])) throw new \Exception("비밀번호가 일치하지 않습니다.");

                // 상태 체크
                if ($user->first()['state'] === 'W') throw new \Exception("관리자 승인 대기중입니다.");

                // 세션 저장
                $result = Session::create([
                    'id' => $user->first()['id'],
                    'password' => $user->first()['password'],
                    'name' => $user->first()['name'],
                    'email' => $user->first()['email'],
                    'salt' => $user->first()['salt'],
                ]);
                if (!$result) throw new \Exception("세션정보 저장에 오류가 발행하였습니다.");

                Session::flash('success', '로그인에 성공하셨습니다');

                Redirect::to('/main');

            } catch (\Exception $e) {
                $response['result'] = false;
                $response['message'] = $e->getMessage();
            }
        }

        view('login', ['input' => $request, 'output' => $response]);
    }
}