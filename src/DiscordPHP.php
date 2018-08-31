<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/8/31
 * Time: 10:17
 */

namespace non0\discord;


class DiscordPHP
{

    const VERSION = '0.1.1';

    /**
     * @var array
     */
    public static $config = array();

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
            $config = self::$config;
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
        self::$config[$name] = $value;
    }
}