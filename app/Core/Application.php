<?php

namespace App\Core;

use App\Core\{ Input, Session, Redirect };

class Application
{
    private $method;

    private $map = [];

    private $setting = [];

    private $response = ['result' => true, 'message' =>'', 'errors' => array()];

    public function __construct($setting = null)
    {
        if (is_array($setting)) {
            $this->setting = $setting;
        }
    }

    public function __call($method, array $arguments)
    {
        $patterns = $this->patterns();

        if (in_array($this->method, ['ALL', 'GET'])) {
            $this->map['GET'][end($patterns['get'])] = array_merge($this->map['GET'][end($patterns['get'])], [$method => $arguments[0]]);
        }

        if (in_array($this->method, ['ALL', 'POST'])) {
            if (array_key_exists(end($patterns['post']), $this->map['POST'])) {
                $this->map['POST'][end($patterns['post'])] = array_merge($this->map['POST'][end($patterns['post'])], [$method => $arguments[0]]);
            }
        }

        return $this;
    }

    public function all($pattern, $callable, $function = null)
    {
        $this->get($pattern, $callable, $function)->post($pattern, $callable, $function);

        $this->method = 'ALL';

        return $this;
    }

    public function get($pattern, $callable, $function = null)
    {
        $this->method = 'GET';

        $this->map($this->method, $pattern, $callable, $function);

        return $this;
    }

    public function post($pattern, $callable, $function = null)
    {
        $this->method = 'POST';

        $this->map($this->method, $pattern, $callable, $function);

        return $this;
    }

    private function map($method, $pattern, $callable, $function)
    {
        $this->map[$method][$pattern] = ['controller' => $callable, 'method' => $function];
    }

    private function patterns()
    {
        $get = $this->map['GET'];
        $getPattern = array_keys($get);

        $post = $this->map['POST'];
        $postPattern = array_keys($post);

        return ['get' => $getPattern, 'post' => $postPattern];
    }

    public function run()
    {
        $requestMethod = Input::server('REQUEST_METHOD');
        $pattern = Input::server('REDIRECT_URL');

        if ($pattern === '/favicon.ico') return;

        if (array_key_exists($pattern, $this->map[$requestMethod])) {

            $controller = $this->map[$requestMethod][$pattern]['controller'];
            $method = $this->map[$requestMethod][$pattern]['method'];

            if (is_null($method)) {
                $controller();
            } else {
                if (method_exists($controller, $method)) {

                    if (!is_null($this->setting)) {
                        $controller = new $controller($this->setting);
                    }

                    if ($requestMethod === 'GET') $request = Input::get();
                    if ($requestMethod === 'POST') $request = Input::post();

                    // 로그인 체크
                    if (array_key_exists('auth', $this->map[$requestMethod][$pattern])) {
                        if ($this->map[$requestMethod][$pattern]['auth']) {
                            if (is_null($GLOBALS['session'])) {
                                Session::flash('fail', '세션정보가 만료되었습니다.');
                                Redirect::to('/');
                            }
                        } else {
                            if (!is_null($GLOBALS['session'])) Redirect::to('/main');
                        }
                    }

                    // 제목
                    if (array_key_exists('title', $this->map[$requestMethod][$pattern])) $GLOBALS['title'] = $this->map[$requestMethod][$pattern]['title'];

                    // 호출
                    call_user_func_array([$controller, $method], [$request, $this->response, Input::exists($requestMethod)]);
                } else {
                    Session::flash('fail', $controller . '에 정의되지 않은 Method 입니다.');
                }
            }
        } else {
            Session::flash('fail', '라우터에 등록되지 않은 경로입니다.');
        }
    }
}