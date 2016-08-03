<?php

namespace App\Core;

use App\Models\User\User;
use App\Models\Code\Code;

class Validator
{
    private $passed = true;

    private $errors = [];

    private $request = [];

    private $name = '';

    private $title = '';

    public function __construct($request = null)
    {
        if (!is_null($request)) {
            $this->request = $request;
        } else {
            throw new \Exception("유효성 검사할 데이터가 확인되지 않습니다.");
        }
    }

    public function check($name = '', $hangul = '')
    {
        $this->passed = true;

        $this->name = $name;

        $this->title = $hangul === '' ? $name : $hangul;

        return $this;
    }

    public function required()
    {
        if ($this->passed === false) return $this;

        if (empty($this->request[$this->name])) {
            $this->addError("{$this->title}을(를) 입력해주세요.");
        }

        return $this;
    }

    public function min($length = 0)
    {
        if ($this->passed === false) return $this;

        if (strlen($this->request[$this->name]) < $length) {
            $this->addError("{$this->title}을(를) {$length}자 이상으로 입력해주세요.");
        }

        return $this;
    }

    public function max($length = 0)
    {
        if ($this->passed === false) return $this;

        if (strlen($this->request[$this->name]) > $length) {
            $this->addError("{$this->title}을(를) {$length}자 미만으로 입력해주세요.");
        }

        return $this;
    }


    public function matches($target = '')
    {
        if ($this->passed === false) return $this;

        if ($this->request[$this->name] != $this->request[$target]) {
            $this->addError("{$this->title}이 일치하지 않습니다.");
        }

        return $this;
    }

    public function email()
    {
        if ($this->passed === false) return $this;

        $result = preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $this->request[$this->name]);
        if (!$result) {
            $this->addError("{$this->title}이 형식에 일치하지 않습니다.");
        }

        return $this;
    }

    public function number()
    {
        if ($this->passed === false) return $this;

        if (!is_numeric($this->request[$this->name])) {
            $this->addError("{$this->title}가 숫자형식으로 되어있지 않습니다.");
        }

        return $this;
    }

    public function isUserId()
    {
        if ($this->passed === false) return $this;

        $count = User::getInstance()->read($this->request[$this->name])->count();
        if ((int)$count > 0) {
            $this->addError("중복 아이디가 있습니다.");
        }

        return $this;
    }

    public function isCode($target)
    {
        if ($this->passed === false) return $this;

        $count = Code::getInstance()->getSubSearch($this->request[$target], $this->request[$this->name])->count();
        if ((int)$count > 0) {
            $this->addError("중복 코드가 있습니다.");
        }

        return $this;
    }

    public function in($value)
    {
        if ($this->passed === false) return $this;

        if (is_array($value)) {
            if (!in_array($this->request[$this->name], $value)) {
                $this->addError("{$this->title}가 범위내에 있지 않습니다.");
            }
        } else {
            if ($this->request[$this->name] != $value) {
                $this->addError("{$this->title}가 정의된 값과 일치하지 않습니다.");
            }
        }

        return $this;
    }

    private function addError($message = '')
    {
        $this->passed = false;

        $this->errors[$this->name] = $message;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passed()
    {
        return count($this->errors) === 0 ? true : false;
    }

    public function exception()
    {
        if (!$this->passed()) {
            throw new \Exception($this->errors[$this->name]);
        }
    }
}