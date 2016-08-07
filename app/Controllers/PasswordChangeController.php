<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User\User;
use App\Core\{ Hash, Input, Session, Redirect, Validator };

class PasswordChangeController extends Controller
{
    public function allPasswordChange(array $request, array $response, bool $exists)
    {
        if ($exists) {
            try {
                // Token 체크하기
                if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

                // 유효성 검사
                $validation = new Validator($request);
                $validation->check('password_current', '현재비밀번호')->required()->min(4)->max(12);
                $validation->check('password', '비밀번호')->required()->min(4)->max(12);
                $validation->check('password_confirm', '비밀번호확인')->required()->matches('password');
                if (!$validation->passed()) {
                    $response['errors'] = $validation->errors();
                    throw new \Exception("파라미터 유효성 검증에 실패하였습니다.");
                }

                // 비밀번호 체크
                if (!password_verify($request['password_current'], $this->session['password'])) throw new \Exception("현재비밀번호가 일치하지 않습니다.");

                // Hash Key 생성
                $salt = Hash::salt(32);

                // 비밀번호 수정
                $result = User::getInstance()->update($this->session['id'], [
                    'password' => Hash::passwordHash($request['password'], $salt),
                    'salt' => $salt,
                    'edt_date' => date('Y-m-d H:i:s')
                ]);
                if (!$result) throw new \Exception("비밀번호 변경에 실패하였습니다.");

                Session::flash('success', '비밀번호 변경에 성공하셨습니다.');

                Redirect::to('/main');

            } catch (\Exception $e) {
                $response['result'] = false;
                $response['message'] = $e->getMessage();
            }
        }

        view('auth/password_change', ['input' => $request, 'output' => $response]);
    }
}