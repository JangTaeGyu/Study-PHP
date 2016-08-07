<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Code\Code;
use App\Core\{ Input, Session, Redirect, Validator };

class CodeController extends Controller
{
    public function getCode(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'mode' => 'insert',
            'code' => 'main',
        ]);

        $response = array_merge($response, [
            'main' => Code::getInstance()->getMainSearch('main')->data(),
            'sub' => Code::getInstance()->getMainSearch($request['code'])->data()
        ]);

        view('auth/menu/code', ['input' => $request, 'output' => $response]);
    }

    public function postCreate(array $request, array $response, bool $exists)
    {
        try {
            // Token 체크하기
            if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

            // 유효성 검사
            $validation = new Validator($request);
            $validation->check('mode', '모드')->required()->in('insert')->exception();
            $validation->check('main', '메인코드')->required()->max(25)->exception();
            $validation->check('sub', '서브코드')->required()->max(25)->isCode('main')->exception();
            $validation->check('name', '명칭')->required()->max(25)->exception();
            $validation->check('state', '상태')->required()->in(['Y', 'N'])->exception();

            // 생성
            $result = Code::getInstance()->create([
                'main' => $request['main'],
                'sub' => $request['sub'],
                'name' => $request['name'],
                'detail' => $request['detail'],
                'state' => $request['state'],
            ]);
            if (!$result) throw new \Exception("코드정보 생성에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '코드생성을 완료하였습니다.' : $response['message']);

        Redirect::to("/menu/code?code={$request['main']}");
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
            $validation->check('main', '메인코드')->required()->max(25)->exception();
            $validation->check('sub', '서브코드')->required()->max(25)->exception();
            $validation->check('name', '명칭')->required()->max(25)->exception();
            $validation->check('state', '상태')->required()->in(['Y', 'N'])->exception();

            // 수정
            $result = Code::getInstance()->update($request['idx'], [
                'main' => $request['main'],
                'sub' => $request['sub'],
                'name' => $request['name'],
                'detail' => $request['detail'],
                'state' => $request['state'],
            ]);
            if (!$result) throw new \Exception("코드정보 수정에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '코드수정을 완료하였습니다.' : $response['message']);

        Redirect::to("/menu/code?code={$request['main']}");
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
            $result = Code::getInstance()->delete($request['idx']);
            if (!$result) throw new \Exception("코드정보 삭제에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '코드삭제를 완료하였습니다.' : $response['message']);

        Redirect::to("/menu/code?code={$request['main']}");
    }
}