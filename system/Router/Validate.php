<?php

namespace System\Router;

class Validate
{
    public $error = [];

    public function __construct($data, $allRules)
    {
        foreach ($allRules as $key => $rules) {
            $value = isset($data[$key]) ? $data[$key] : null;
            if (empty($value)) {
                if (strpos($rules, 'required') !== false) {
                    $this->error[$key][] = ['name' => 'required'];
                }
                continue;
            }
            $rules = explode('|', $rules);

            $this->error[$key] = [];
            foreach ($rules as $rule) {
                if (strpos($rule, 'required') !== false) {
                    continue;
                }
                if (strpos($rule, ':') != false) {
                    [$ruleName, $ruleValue] = explode(':', $rule);

                    if (method_exists($this, $ruleName)) {
                        $this->$ruleName($key, $value, $ruleValue);
                    }
                } else {
                    if (method_exists($this, $rule)) {
                        $this->$rule($key, $value);
                    }
                }
            }
            if (empty($this->error[$key])) {
                unset($this->error[$key]);
            }
        }
    }

    private function min($key, $value, $ruleValue)
    {
        if (strlen($value) < $ruleValue) {
            $this->error[$key][] = ['name' => 'min', 'value' => intval($ruleValue), 'length' => strlen($value)];
        }
    }

    private function max($key, $value, $ruleValue)
    {
        if (strlen($value) > $ruleValue) {
            $this->error[$key][] = ['name' => 'max', 'value' => intval($ruleValue), 'length' => strlen($value)];
        }
    }

    private function in($key, $value, $ruleValue)
    {
        $ruleValue = explode(',', $ruleValue);
        if (!in_array($value, $ruleValue)) {
            $this->error[$key][] = ['name' => 'in', 'value' => $ruleValue];
        }
    }

    private function notIn($key, $value, $ruleValue)
    {
        $ruleValue = explode(',', $ruleValue);
        if (in_array($value, $ruleValue)) {
            $this->error[$key][] = ['name' => 'notIn', 'value' => $ruleValue];
        }
    }

    private function email($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->error[$key][] = ['name' => 'email', 'value' => $value];
        }
    }

    private function number($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->error[$key][] = ['name' => 'number', 'value' => $value];
        }
    }

    private function hostname($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_DOMAIN)) {
            $this->error[$key][] = ['name' => 'hostname', 'value' => $value];
        }
    }

    private function url($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->error[$key][] = ['name' => 'url', 'value' => $value];
        }
    }

    private function ip($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_IP)) {
            $this->error[$key][] = ['name' => 'ip', 'value' => $value];
        }
    }

    private function ipv4($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->error[$key][] = ['name' => 'ipv4', 'value' => $value];
        }
    }

    private function ipv6($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->error[$key][] = ['name' => 'ipv6', 'value' => $value];
        }
    }

    private function privateIP($key, $value)
    {
        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            $this->error[$key][] = ['name' => 'privateIP', 'value' => $value];
        }
    }

    private function publicIP($key, $value)
    {
        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
            $this->error[$key][] = ['name' => 'publicIP', 'value' => $value];
        }
    }

    private function mac($key, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_MAC)) {
            $this->error[$key][] = ['name' => 'mac', 'value' => $value];
        }
    }

    private function uuid($key, $value)
    {
        if (!preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $value)) {
            $this->error[$key][] = ['name' => 'uuid', 'value' => $value];
        }
    }
}
