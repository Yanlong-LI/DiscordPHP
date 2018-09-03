<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/8/31
 * Time: 10:17
 */

namespace discord;


use discord\support\Config;
use discord\support\Lang;
use discord\support\Log;

class DiscordPHP
{
    const START_SERVER = 0;
    const START_WEB = 1;
    const START_PROXY = 2;

    //discordphp 核心根目录
    /**
     * @var string
     */
    public static $discord_path = __DIR__;

    /**
     * @var array
     */
    public static $config = array();

    /**
     * @var int
     */
    public static $start_type;

    //统一入口
    public static function run($start_type = self::START_WEB)
    {
        self::$start_type = $start_type;
        //初始化配置
        Config::init();
    }

    public static function start()
    {

    }


}