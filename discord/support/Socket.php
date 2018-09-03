<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/9/3
 * Time: 13:40
 * PHP SOCKET 注意本类仅限于用于接入或提供Server，无法在单任务项目中使用。
 */

namespace discord\support;


use discord\DiscordPHP;

class Socket
{
    /**
     * @var resource
     */
    public $resource;

    public $discordSocket;

    public $clientSockets;

    /**
     * Socket constructor.
     */
    public function __construct()
    {
        /**
         * AF_INET ipv4
         * AF_INET6 ipv6
         */
        $this->resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    public function start()
    {
        socket_connect($this->resource, DiscordPHP::getConfig('socket.discord.host'), DiscordPHP::getConfig('socket.discord.port'));

        while (TRUE) {
            $r = array($this->resource);
            $c = socket_select($r, $w = NULL, $e = NULL, 5);

            foreach ($r as $read_socket) {
                if ($r = $this->negotiate($read_socket)) {
                    var_dump($r);
                    exit;
                }
            }
        }
    }

    function negotiate($socket)
    {
        socket_recv($socket, $buffer, 1024, 0);

        for ($chr = 0; $chr < strlen($buffer); $chr++) {
            if ($buffer[$chr] == chr(255)) {

                $send = (isset($send) ? $send . $buffer[$chr] : $buffer[$chr]);

                $chr++;
                if (in_array($buffer[$chr], array(chr(251), chr(252)))) $send .= chr(254);
                if (in_array($buffer[$chr], array(chr(253), chr(254)))) $send .= chr(252);

                $chr++;
                $send .= $buffer[$chr];
            } else {
                break;
            }
        }

        if (isset($send)) socket_send($socket, $send, strlen($send), 0);
        if ($chr - 1 < strlen($buffer)) return substr($buffer, $chr);

    }
}