<?php

namespace App\Core;

use App\Models\User\UserSessions;

class Session
{
    public static function exists($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public static function get($name)
    {
        return self::exists($name) ? $_SESSION[$name] : '';
    }

    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function flash($name, $string = '')
    {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }

    public static function isLogin()
    {
        $session = UserSessions::getInstance()->read(session_id());
        if ($session->count() > 0) {
            return true;
        }

        return false;
    }

    public static function create(array $args = [])
    {
        if (self::isLogin()) {
            UserSessions::getInstance()->delete(session_id());
        }

        return UserSessions::getInstance()->create([
            'session_id' => session_id(),
            'session_expires' => time() + (get_cfg_var("session.gc_maxlifetime") * 60),
            'id' => $args['id'],
            'password' => $args['password'],
            'name' => $args['name'],
            'email' => $args['email'],
            'salt' => $args['salt'],
        ]);
    }

    public static function destroy()
    {
        if ((boolean)ini_get("session.use_cookies")) {

            // 세션 삭제
            $result = UserSessions::getInstance()->delete(session_id());
            if ($result) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
        }
    }
}