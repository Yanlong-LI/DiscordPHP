<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/8/31
 * Time: 14:18
 */

namespace discord\support;


use discord\DiscordPHP;

class Lang
{
    /**
     * 多语言信息
     * @var array
     */
    private static $lang = [];

    /**
     * 当前语言
     * @var string
     */
    private static $range = 'zh-cn';

    // 设定当前的语言
    public static function range($range = '')
    {
        if ('' == $range) {
            return self::$range;
        } else {
            self::$range = $range;
        }
    }

    /**
     * 设置语言定义(不区分大小写)
     * @access public
     * @param  string|array $name 语言变量
     * @param  string $value 语言值
     * @param  string $range 语言作用域
     * @return mixed
     */
    public static function set($name, $value = null, $range = '')
    {
        $range = $range ?: self::$range;
        // 批量定义
        if (!isset(self::$lang[$range])) {
            self::$lang[$range] = [];
        }

        if (is_array($name)) {
            return self::$lang[$range] = array_change_key_case($name) + self::$lang[$range];
        }

        return self::$lang[$range][strtolower($name)] = $value;
    }

    /**
     * 获取语言定义(不区分大小写)
     * @access public
     * @param  string|null $name 语言变量
     * @param  string $range 语言作用域
     * @return bool
     */
    public static function has($name, $range = '')
    {
        $range = $range ?: self::$range;

        return isset(self::$lang[$range][strtolower($name)]);
    }

    /**
     * 获取语言定义(不区分大小写)
     * @access public
     * @param  string|null $name 语言变量
     * @param  array $vars 变量替换
     * @param  string $range 语言作用域
     * @return mixed
     */
    public static function get($name = null, $vars = [], $range = '')
    {
        $range = $range ?: self::$range;

        // 空参数返回所有定义
        if (empty($name)) {
            return self::$lang[$range];
        }
        if (!isset(self::$lang[$range])) {
            self::load(DiscordPHP::$discord_path . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $range . '.php', $range);
        }

        $key = strtolower($name);
        $value = isset(self::$lang[$range][$key]) ? self::$lang[$range][$key] : $name;

        // 变量解析
        if (!empty($vars) && is_array($vars)) {
            /**
             * Notes:
             * 为了检测的方便，数字索引的判断仅仅是参数数组的第一个元素的key为数字0
             * 数字索引采用的是系统的 sprintf 函数替换，用法请参考 sprintf 函数
             */
            if (key($vars) === 0) {
                // 数字索引解析
                array_unshift($vars, $value);
                $value = call_user_func_array('sprintf', $vars);
            } else {
                // 关联索引解析
                $replace = array_keys($vars);
                foreach ($replace as &$v) {
                    $v = "{:{$v}}";
                }
                $value = str_replace($replace, $vars, $value);
            }
        }

        return $value;
    }

    /**
     * 加载语言定义(不区分大小写)
     * @access public
     * @param  string|array $file 语言文件
     * @param  string $range 语言作用域
     * @return array
     */
    public static function load($file, $range = '')
    {
        $range = $range ?: self::$range;
        if (!isset(self::$lang[$range])) {
            self::$lang[$range] = [];
        }

        // 批量定义
        if (is_string($file)) {
            $file = [$file];
        }

        $lang = [];

        foreach ($file as $_file) {
            if (file_exists($_file)) {
                // 记录加载信息
                Log::debug('加载语言包 lang:' . $_file);
                $_lang = include $_file;
                if (is_array($_lang)) {
                    $lang = array_change_key_case($_lang) + $lang;
                }
            }
        }

        if (!empty($lang)) {
            self::$lang[$range] = $lang + self::$lang[$range];
        }

        return self::$lang[$range];
    }
}