<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library;

/**
 * Description of Ajax
 *
 * @author admin
 */
class Ajax
{

    public function __construct($param = false, $value = false)
    {
        $this->params = array();
        if ($param) {
            $this->appendParam($param, $value);
        }
        // $this->appendParam($param, $value);
    }

    public function success($value = TRUE)
    {
        $this->appendParam("success", $value);
        return $this;
    }

    public function form_reset($value = FALSE)
    {
        $this->appendParam("form_reset", $value);
        return $this;
    }

    public function fail()
    {
        $this->appendParam("success", FALSE);
        return $this;
    }

    public function form_errors($value)
    {
        $this->appendParam("form_errors", $value)->fail();
        return $this;
    }


    const CALLBACK_GENERAL = 'general_form';

    public function jscallback($param = self::CALLBACK_GENERAL)
    {
        $this->appendParam("completefn", $param);
        return $this;
    }

    const MESSAGE_TYPE_SUCCESS = "success";
    const MESSAGE_TYPE_ERROR = "error";
    const MESSAGE_TYPE_WARNING = "warning";
    const MESSAGE_TYPE_INFO = "info";

    public function message($messageTitle, $messageDescription = "", $messageType = Ajax::MESSAGE_TYPE_SUCCESS)
    {
        $this->appendParam("message", TRUE)->appendParam("messageTitle", $messageTitle)->appendParam("messageDescription", $messageDescription)->appendParam("messageType", $messageType);
        return $this;
    }

    public function reload_page($value = TRUE)
    {
        $this->appendParam("page_reload", $value);
        return $this;
    }

    public function redirectTo($url)
    {
        $this->appendParam("redirect", TRUE)->appendParam("redirectURL", $url);
        return $this;
    }

    public function appendParam($param, $value = false)
    {
        if (is_array($param)) {
            foreach ($param as $key => $val) {
                $this->appendParam($key, $val);
            }
        } else {
            $this->params [$param] = $value;
        }
        return $this;
    }

    public static function create($param = false, $value = false)
    {
        $obj = new Ajax ();
        if ($param) {
            $obj->appendParam($param, $value);
        }
        return $obj;
    }

    // put your code here
    public function response($param = false, $value = false)
    {
        if ($param) {
            $this->appendParam($param, $value);
        }/*
          echo json_encode ( $this->params ); */
        //exit ();
        return response()->json($this->params);
    }

}
