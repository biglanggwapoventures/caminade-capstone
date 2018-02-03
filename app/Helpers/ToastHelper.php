<?php

namespace App\Helpers;

use Session;

class ToastHelper
{
    public static function success($message, $title = null)
    {
        Session::flash('__TOAST__', [
            'type' => 'success',
            'message' => $message,
            'title' => $title,
        ]);
        return new static;
    }

    public static function error($message, $title = null)
    {
        Session::flash('__TOAST__', [
            'type' => 'error',
            'message' => $message,
            'title' => $title,
        ]);
        return new static;
    }
}
