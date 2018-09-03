<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/9/3
 * Time: 14:58
 */

namespace app\server\controller;


use non0\discord\support\Socket;

class Index
{
    public function index()
    {
        $socket = new Socket();
        $socket->start();
    }
}