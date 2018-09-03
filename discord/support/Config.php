<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/9/3
 * Time: 16:00
 */

namespace discord\support;


use discord\DiscordPHP;

class Config
{
    /**
     * 获取配置参数
     * 兼容 key.key
     * key. 获取key下的所有数据 value
     * @param $name
     * @param null $default
     * @param null $config
     * @return array|mixed|null
     */
    public static function getConfig($name = '', $default = null, $config = null)
    {
        if ($config == null) {
            $config = DiscordPHP::$config;
        }
        $name = explode('.', $name);
        if (count($name) == 1) {
            if (trim($name[0]) == '') return $config;
            return isset($config[$name[0]]) ? $config[$name[0]] : $default;
        } else {
            if (isset($config[$name[0]])) {
                $newname = $name[0];
                unset($name[0]);
                $name = implode('.', $name);
            }
            return self::getConfig($name, null, $config[$newname]);
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public static function setConfig($name, $value)
    {
        DiscordPHP::$config[$name] = $value;
    }

    /**
     * 初始化核心配置文件
     */
    public static function init()
    {
        //加载配置
        DiscordPHP::$config = require DiscordPHP::$discord_path.'/config/discordphp.php';
        //配置加载完成
        Log::debug(Lang::get('configure') . Lang::get('load') . Lang::get('complete'));
        //设定默认语言
        Lang::range(Config::getConfig('lang', 'zh-cn'));
    }
}