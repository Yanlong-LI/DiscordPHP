<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/8/31
 * Time: 14:18
 */

namespace non0\discord\support;


class Lang
{
    /**
     * 多语言信息
     * @var array
     */
    private $lang = [];

    /**
     * 当前语言
     * @var string
     */
    private $range = 'zh-cn';

    // 设定当前的语言
    public function range($range = '')
    {
        if ('' == $range) {
            return $this->range;
        } else {
            $this->range = $range;
        }
    }

    /**
     * 设置语言定义(不区分大小写)
     * @access public
     * @param  string|array  $name 语言变量
     * @param  string        $value 语言值
     * @param  string        $range 语言作用域
     * @return mixed
     */
    public function set($name, $value = null, $range = '')
    {
        $range = $range ?: $this->range;
        // 批量定义
        if (!isset($this->lang[$range])) {
            $this->lang[$range] = [];
        }

        if (is_array($name)) {
            return $this->lang[$range] = array_change_key_case($name) + $this->lang[$range];
        }

        return $this->lang[$range][strtolower($name)] = $value;
    }

    /**
     * 获取语言定义(不区分大小写)
     * @access public
     * @param  string|null   $name 语言变量
     * @param  string        $range 语言作用域
     * @return bool
     */
    public function has($name, $range = '')
    {
        $range = $range ?: $this->range;

        return isset($this->lang[$range][strtolower($name)]);
    }

    /**
     * 获取语言定义(不区分大小写)
     * @access public
     * @param  string|null   $name 语言变量
     * @param  array         $vars 变量替换
     * @param  string        $range 语言作用域
     * @return mixed
     */
    public function get($name = null, $vars = [], $range = '')
    {
        $range = $range ?: $this->range;

        // 空参数返回所有定义
        if (empty($name)) {
            return $this->lang[$range];
        }

        $key   = strtolower($name);
        $value = isset($this->lang[$range][$key]) ? $this->lang[$range][$key] : $name;

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
     * @param  string|array  $file   语言文件
     * @param  string        $range  语言作用域
     * @return array
     */
    public function load($file, $range = '')
    {
        $range = $range ?: $this->range;
        if (!isset($this->lang[$range])) {
            $this->lang[$range] = [];
        }

        // 批量定义
        if (is_string($file)) {
            $file = [$file];
        }

        $lang = [];

        foreach ($file as $_file) {
            if (is_file($_file)) {
                // 记录加载信息
                //$this->app->log('[ LANG ] ' . $_file);
                $_lang = include $_file;
                if (is_array($_lang)) {
                    $lang = array_change_key_case($_lang) + $lang;
                }
            }
        }

        if (!empty($lang)) {
            $this->lang[$range] = $lang + $this->lang[$range];
        }

        return $this->lang[$range];
    }
}