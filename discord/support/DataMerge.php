<?php
/**
 * Created by PhpStorm.
 * User: Yanlongli
 * Date: 2018/8/31
 * Time: 13:53
 * 递归合并数组或其他
 * 常规的array_merge合并会导致多维数据子维丢失问题，递归合并可能影响到执行效率，但忽略不计，除非你的数据真的很大。
 */

namespace discord\support;


class DataMerge
{

    /**
     * @param $a array|string
     * @param $b string|array
     */
    public static function ArrayMerge(&$a, $b)
    {

        foreach ($a as $key => &$val) {
            if (is_array($val) && array_key_exists($key, $b) && is_array($b[$key])) {
                self::ArrayMerge($val, $b[$key]);
                $val = $val + $b[$key];
            } else if (is_array($val) || (array_key_exists($key, $b) && is_array($b[$key]))) {
                $val = is_array($val) ? $val : $b[$key];
            }
        }
        $a = $a + $b;
    }

    /**
     * @param $a string
     * @param $b string
     */
    public static function JsonMerge(&$a, $b)
    {
        self::ArrayMerge(json_decode($a,true), json_decode($b,true));
    }
}