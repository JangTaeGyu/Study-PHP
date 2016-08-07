<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\{ Code\Code, Member\Member, Task\Task };
use App\Core\{ Input, Session, Redirect, Validator };

class TaskController extends Controller
{
    public function getTask(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'target' => '',
            'kind' => '',
            'title' => '',
            'member_idx' => '',
            'state' => '',
            'sdate' => date('Y-m-d', strtotime('-6day')),
            'edate' => date('Y-m-d'),
        ]);

        $response = array_merge($response, [
            'code' => Code::getInstance()->getMultiMainSearch(['target', 'kind']),
            'member' => Member::getInstance()->getMemberSearch()->data(),
            'task' => Task::getInstance()->getTaskSearch($request)->data()
        ]);

        view('auth/menu/task/index', ['input' => $request, 'output' => $response]);
    }

    public function getWaiting(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'target' => '',
            'kind' => '',
            'title' => '',
            'member_idx' => '',
            'state' => 'W',
            'sdate' => '',
            'edate' => ''
        ]);

        $response = array_merge($response, [
            'code' => Code::getInstance()->getMultiMainSearch(['target', 'kind']),
            'member' => Member::getInstance()->getMemberSearch()->data(),
            'task' => Task::getInstance()->getTaskSearch($request)->data()
        ]);

        view('auth/menu/task/waiting', ['input' => $request, 'output' => $response]);
    }

    public function getComplete(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'target' => '',
            'kind' => '',
            'title' => '',
            'member_idx' => '',
            'state' => 'S',
            'sdate' => date('Y-m-d', strtotime('-6day')),
            'edate' => date('Y-m-d')
        ]);

        $response = array_merge($response, [
            'code' => Code::getInstance()->getMultiMainSearch(['target', 'kind']),
            'member' => Member::getInstance()->getMemberSearch()->data(),
            'task' => Task::getInstance()->getTaskSearch($request, true)->data()
        ]);

        view('auth/menu/task/complete', ['input' => $request, 'output' => $response]);
    }

    public function getIssue(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'target' => '',
            'kind' => '',
            'title' => '',
            'member_idx' => '',
            'state' => 'S',
            'sdate' => date('Y-m-d', strtotime('-6day')),
            'edate' => date('Y-m-d')
        ]);

        $response = array_merge($response, [
            'code' => Code::getInstance()->getMultiMainSearch(['target', 'kind']),
            'member' => Member::getInstance()->getMemberSearch()->data(),
            'task' => Task::getInstance()->getTaskIssue()->data()
        ]);

        view('auth/menu/task/issue', ['input' => $request, 'output' => $response]);
    }

    public function getInput(array $request, array $response, bool $exists)
    {
        $request = elms($request, [
            'idx' => '',
            'mode' => 'insert'
        ]);

        $response = array_merge($response, [
            'action' => '/menu/task/create',
            'code' => Code::getInstance()->getMultiMainSearch(['target', 'kind']),
            'member' => Member::getInstance()->getMemberSearch()->data()
        ]);
        if ($request['idx'] != '') {
            $request['mode'] = 'update';

            $task = Task::getInstance()->read($request['idx'])->first();

            $response = array_merge($response, [
                'action' => '/menu/task/update',
                'info' => $task,
                'task' => Task::getInstance()->getTaskSearch(['target' => $task['target'], 'kind' => '', 'title' => '', 'member_idx' => '', 'state' => '', 'sdate' => '', 'edate' => ''])->data()
            ]);
        } else {
            $response = array_merge($response, ['info' => array()]);
        }

        view('auth/menu/task/input', ['input' => $request, 'output' => $response]);
    }

    public function postCreate(array $request, array $response, bool $exists)
    {
        try {
            // Token 체크하기
            if ($this->csrf::check(Input::post('token')) === false) throw new \Exception("토큰정보가 일치하지 않습니다.");

            // 유효성 검사
            $validation = new Validator($request);
            $validation->check('mode', '모드')->required()->in('insert')->exception();
            $validation->check('target', '업무대상')->required()->max(50)->exception();
            $validation->check('kind', '업무분류')->required()->max(50)->exception();
            $validation->check('member_idx', '요청자')->required()->number()->exception();
            $validation->check('title', '제목')->required()->max(200)->exception();
            $validation->check('state', '상태')->required()->in(['W', 'S', 'N'])->exception();

            // 생성
            $result = Task::getInstance()->create([
                'target' => $request['target'],
                'kind' => $request['kind'],
                'member_idx' => $request['member_idx'],
                'title' => $request['title'],
                'contents' => $request['contents'],
                'state' => $request['state'],
                'issue' => array_key_exists('issue', $request) ? $request['issue'] : 'N',
                'complete_date' => $request['state'] === 'S' ? date('Y-m-d') : '',
                'date' => date('Y-m-d H:i:s'),
            ]);
            if (!$result) throw new \Exception("업무등록에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '업무등록 완료하였습니다.' : $response['message']);

        Redirect::to($response['result'] ? '/menu/task' : '/menu/task/input');
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
            $validation->check('target', '업무대상')->required()->max(50)->exception();
            $validation->check('kind', '업무분류')->required()->max(50)->exception();
            $validation->check('member_idx', '요청자')->required()->number()->exception();
            $validation->check('title', '제목')->required()->max(200)->exception();
            $validation->check('state', '상태')->required()->in(['W', 'S', 'N'])->exception();

            $params = [
                'target' => $request['target'],
                'kind' => $request['kind'],
                'member_idx' => $request['member_idx'],
                'title' => $request['title'],
                'contents' => $request['contents'],
                'state' => $request['state'],
                'issue' => array_key_exists('issue', $request) ? $request['issue'] : 'N',
                'complete_date' => $request['state'] === 'S' ? date('Y-m-d') : '',
            ];
            if ($request['before_state'] === 'S') unset($params['complete_date']);

            // 생성
            $result = Task::getInstance()->update($request['idx'], $params);
            if (!$result) throw new \Exception("업무수정에 실패하였습니다.");

        } catch (\Exception $e) {
            $response['result'] = false;
            $response['message'] = $e->getMessage();
        }

        Session::flash($response['result'] ? 'success' : 'fail', $response['result'] ? '업무수정 완료하였습니다.' : $response['message']);

        Redirect::to($response['result'] ? '/menu/task' : '/menu/task/input?idx=' . $request['idx']);
    }
}