<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User\{ User, UserSessions };
use App\Core\{ Input, Session, Redirect, Validator };

class UpdateController extends Controller
{
    public function update(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'name' => $this->session['name'],       // 이름
            'email' => $this->session['email'],     // 비밀번호
        ]);

        if ($exists) {
            try {
                // Token 체크하기
                if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

                // 유효성 검사
                $validation = new Validator($request);
                $validation->check('name', '이름')->required()->min(2)->max(12);
                $validation->check('email', '이메일')->required()->email();
                if (!$validation->passed()) {
                    $response['errors'] = $validation->errors();
                    throw new \Exception("파라미터 유효성 검증에 실패하였습니다.");
                }

                // 회원정보 수정
                $result = User::getInstance()->update($this->session['id'], [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'edt_date' => date('Y-m-d H:i:s')
                ]);
                if (!$result) throw new \Exception("정보수정에 실패하였습니다.");

                // 세션정보 수정
                $result = UserSessions::getInstance()->update(session_id(), [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'date' => date('Y-m-d H:i:s')
                ]);
                if (!$result) throw new \Exception("세션정보수정에 실패하였습니다.");

                Session::flash('success', '정보수정에 성공하셨습니다.');

                Redirect::to('/main');

            } catch (\Exception $e) {
                $response['result'] = false;
                $response['message'] = $e->getMessage();
            }
        }

        view('auth/update', ['input' => $request, 'output' => $response]);
    }
}