<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\{ Code\Code, Member\Member };
use App\Core\{ Input, Session, Redirect, Validator };

class MemberController extends Controller
{
    public function getMember(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'mode' => 'insert',
            'company' => 'edupre',
        ]);

        $response = array_merge($response, [
            'code' => Code::getInstance()->getMultiMainSearch(['company', 'department', 'rank']),
            'member' => Member::getInstance()->getMemberSearch()->data()
        ]);

        view('auth/menu/member', ['input' => $request, 'output' => $response]);
    }

    public function postCreate(array $request, array $response, bool $exists)
    {
        try {
            // Token 체크하기
            if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

            // 유효성 검사
            $validation = new Validator($request);
            $validation->check('mode', '모드')->required()->in('insert')->exception();
            $validation->check('company', '회사')->required()->max(50)->exception();
            $validation->check('department', '부서')->required()->max(50)->exception();
            $validation->check('rank', '직급')->required()->max(50)->exception();
            $validation->check('name', '이름')->required()->max(50)->exception();
            $validation->check('state', '상태')->required()->in(['Y', 'N'])->exception();

            // 생성
            $result = Member::getInstance()->create([
                'company' => $request['company'],
                'department' => $request['department'],
                'rank' => $request['rank'],
                'name' => $request['name'],
                'call' => $request['call'],
                'state' => $request['state'],
            ]);
            if (!$result) throw new \Exception("회원정보 생성에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '회원생성을 완료하였습니다.' : $response['message']);

        Redirect::to("/menu/member");
    }

    public function postUpdate(array $request, array $response, bool $exists)
    {
        try {
            // Token 체크하기
            if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

            // 유효성 검사
            $validation = new Validator($request);
            $validation->check('mode', '모드')->required()->in('update')->exception();
            $validation->check('idx', '인덱스')->required()->number()->exception();
            $validation->check('company', '회사')->required()->max(50)->exception();
            $validation->check('department', '부서')->required()->max(50)->exception();
            $validation->check('rank', '직급')->required()->max(50)->exception();
            $validation->check('name', '이름')->required()->max(50)->exception();
            $validation->check('state', '상태')->required()->in(['Y', 'N'])->exception();

            // 수정
            $result = Member::getInstance()->update($request['idx'], [
                'company' => $request['company'],
                'department' => $request['department'],
                'rank' => $request['rank'],
                'name' => $request['name'],
                'call' => $request['call'],
                'state' => $request['state'],
            ]);
            if (!$result) throw new \Exception("회원정보 수정에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '회원수정을 완료하였습니다.' : $response['message']);

        Redirect::to("/menu/member");
    }

    public function postDelete(array $request, array $response, bool $exists)
    {
        try {
            // Token 체크하기
            if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

            // 유효성 검사
            $validation = new Validator($request);
            $validation->check('mode', '모드')->required()->in('delete')->exception();
            $validation->check('idx', '인덱스')->required()->number()->exception();

            // 삭제
            $result = Member::getInstance()->delete($request['idx']);
            if (!$result) throw new \Exception("회원정보 삭제에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '회원삭제를 완료하였습니다.' : $response['message']);

        Redirect::to("/menu/member");
    }
}