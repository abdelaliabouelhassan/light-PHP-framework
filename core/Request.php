<?php

namespace App\core;

use App\models\User;

class Request extends Session
{



    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position =  strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }


    public function all()
    {
        $body = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } else if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        //return $body as array
        return $body;
        return json_encode($body);
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }


    public function rules()
    {
        return [
            'confirmed' => '$field not match the confirmation password',
            'required' => '$field is required',
            'min' => '$field must be at least $min characters',
            'max' => '$field must be at most $max characters',
            'email' => '$field must be a valid email address',
            'unique' => '$field must be unique',
            'alpha' => '$field must be alphabetic',
            'numeric' => '$field must be numeric',
            'alphanumeric' => '$field must be alphanumeric',
            'url' => '$field must be a valid URL',
            'ip' => '$field must be a valid IP address',
            'mac' => '$field must be a valid MAC address',
            'date' => '$field must be a valid date',
            'time' => '$field must be a valid time',
            'datetime' => '$field must be a valid datetime',
            'boolean' => '$field must be a valid boolean',
            'float' => '$field must be a valid float',
            'integer' => '$field must be a valid integer',
            'regex' => '$field must be a valid regex',
            '_in' => '$field must be one of the following: $in',
            'not_in' => '$field must not be one of the following: $not_in',
            'between' => '$field must be between $min and $max',
            'not_between' => '$field must not be between $min and $max',
            'contains' => '$field must contain $contains',
            'not_contains' => '$field must not contain $not_contains',
            'starts_with' => '$field must start with $starts_with',
            'not_starts_with' => '$field must not start with $not_starts_with',
            'ends_with' => '$field must end with $ends_with',
            'not_ends_with' => '$field must not end with $not_ends_with',
            'is_empty' => '$field must be empty',
            'is_not_empty' => '$field must not be empty',
            'is_null' => '$field must be null',
            'is_not_null' => '$field must not be null',
            'is_true' => '$field must be true',
            'is_false' => '$field must be false',
            'is_iterable' => '$field must be iterable',
            'is_not_iterable' => '$field must not be iterable',
            'is_callable' => '$field must be callable',
            'is_not_callable' => '$field must not be callable',
            'is_array' => '$field must be an array',
            'is_not_array' => '$field must not be an array',
            'is_object' => '$field must be an object',
            'is_not_object' => '$field must not be an object',
            'is_resource' => '$field must be a resource',
            'is_not_resource' => '$field must not be a resource',
            'is_scalar' => '$field must be a scalar',
            'is_not_scalar' => '$field must not be a scalar',
            'is_numeric' => '$field must be numeric',
            'is_not_numeric' => '$field must not be numeric',
            'is_float' => '$field must be a float',
            'is_not_float' => '$field must not be a float',
            'is_int' => '$field must be an integer',
            'is_not_int' => '$field must not be an integer',
            'is_bool' => '$field must be a boolean',
            'is_not_bool' => '$field must not be a boolean',
            'is_string' => '$field must be a string',
            'is_not_string' => '$field must not be a string',

        ];
    }


    public function validate($rules)
    {
        $errors = [];
        $body = $this->all();
        $allRulesMessage = $this->rules();
        foreach ($rules as $key => $value) {
            foreach ($value as $rule) {
                if ($rule == 'required') {
                    if (!isset($body[$key]) || $body[$key] == '') {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['required']);
                    }
                }

                if ($rule == 'confirmed') {
                    if (isset($body[$key . '_confirmation']))
                        if (!isset($body[$key]) || $body[$key] != $body[$key . '_confirmation']) {
                            $errors[$key][] = str_replace('$field', $key, $allRulesMessage['confirmed']);
                        }
                }

                if (strpos($rule, 'min:') !== false) {
                    $min = $value[1];
                    $min = str_replace('min:', '', $min);
                    if (isset($body[$key]) && strlen($body[$key]) <  $min) {
                        $message = str_replace('$min', $min, $allRulesMessage['min']);
                        $errors[$key][] = str_replace('$field', $key, $message);
                    }
                }

                if (strpos($rule, 'max:') !== false) {
                    $max = $value[1];
                    $max = str_replace('max:', '', $max);
                    if (isset($body[$key]) && strlen($body[$key]) > $max) {
                        $message = str_replace('$max', $max, $allRulesMessage['max']);
                        $errors[$key][] = str_replace('$field', $key, $message);
                    }
                }

                if ($rule == 'email') {
                    if (isset($body[$key]) && !filter_var($body[$key], FILTER_VALIDATE_EMAIL)) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['email']);
                    }
                }

                if ($rule == 'unique') {
                    if (isset($body[$key]) && $body[$key] != '') {
                        $user = User::findByEmail($body[$key]);
                        if ($user) {
                            $errors[$key][] = str_replace('$field', $key, $allRulesMessage['unique']);
                        }
                    }
                }

                if ($rule == 'alpha') {
                    if (isset($body[$key]) && !preg_match('/^[a-zA-Z]+$/', $body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['alpha']);
                    }
                }

                if ($rule == 'numeric') {
                    if (isset($body[$key]) && !is_numeric($body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['numeric']);
                    }
                }

                if ($rule == 'alphanumeric') {
                    if (isset($body[$key]) && !preg_match('/^[a-zA-Z0-9]+$/', $body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['alphanumeric']);
                    }
                }

                if ($rule == 'url') {
                    if (isset($body[$key]) && !filter_var($body[$key], FILTER_VALIDATE_URL)) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['url']);
                    }
                }

                if ($rule == 'ip') {
                    if (isset($body[$key]) && !filter_var($body[$key], FILTER_VALIDATE_IP)) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['ip']);
                    }
                }

                if ($rule == 'mac') {
                    if (isset($body[$key]) && !preg_match('/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i', $body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['mac']);
                    }
                }

                if ($rule == 'date') {
                    if (isset($body[$key]) && !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['date']);
                    }
                }

                if ($rule == 'time') {
                    if (isset($body[$key]) && !preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/', $body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['time']);
                    }
                }

                if ($rule == 'datetime') {
                    if (isset($body[$key]) && !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/', $body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['datetime']);
                    }
                }

                if ($rule == 'boolean') {
                    if (isset($body[$key]) && !is_bool($body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['boolean']);
                    }
                }

                if ($rule == 'float') {
                    if (isset($body[$key]) && !is_float($body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['float']);
                    }
                }

                if ($rule == 'integer') {
                    if (isset($body[$key]) && !is_int($body[$key])) {
                        $errors[$key][] = str_replace('$field', $key, $allRulesMessage['integer']);
                    }
                }

                if (strpos($rule, 'regex:') !== false) {
                    $regex = $value[1];
                    $regex = str_replace('regex:', '', $regex);
                    if (isset($body[$key]) && !preg_match($regex, $body[$key])) {
                        $message = str_replace('$regex', $regex, $allRulesMessage['regex']);
                        $errors[$key][] = str_replace('$field', $key, $message);
                    }
                }


                if (strpos($rule, '_in:') !== false) {
                    $in = $value[1];
                    $in = str_replace('_in:', '', $in);
                    $in = explode(',', $in);
                    if (isset($body[$key]) && !in_array($body[$key], $in)) {
                        $message = str_replace('$in', $in, $allRulesMessage['in']);
                        $errors[$key][] = str_replace('$field', $key, $message);
                    }
                }

                if (strpos($rule, 'not_in:') !== false) {
                    $notin = $value[1];
                    $notin = str_replace('notin:', '', $notin);
                    $notin = explode(',', $notin);
                    if (isset($body[$key]) && in_array($body[$key], $notin)) {
                        $message = str_replace('$notin', $notin, $allRulesMessage['notin']);
                        $errors[$key][] = str_replace('$field', $key, $message);
                    }
                }

                //add more rules here

            }
        }


        // if there is errors redirect back to the form
        if (count($errors) > 0) {
            // set the errors to session flashdata
            $this->flash('errors', $errors);
        
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }else{
            return true;
        }
    }
}
