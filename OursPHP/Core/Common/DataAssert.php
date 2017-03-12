<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/4
 * Time: 0:53
 * Doc: 数据断言
 */
namespace OursPHP\Core\Common;

class DataAssert {


    /**
     * 数据为空
     * @param $var
     * @param BizException $e
     * @throws BizException
     */
    public static function assertNotEmpty($var, BizException $e) {
        if (empty($var))
            throw $e;
    }

    /**
     * 数据为True
     * @param $x
     * @param BizException $e
     * @throws BizException
     */
    public static function assertTrue($x, BizException $e) {
        if (!$x)
            throw $e;
    }

    /**
     * 数据为F
     * @param $x
     * @param BizException $e
     * @throws BizException
     */
    public static function assertFalse($x, BizException $e) {
        if ($x)
            throw $e;
    }

    /**
     * 数据为null
     * @param $x
     * @param BizException $e
     * @throws BizException
     */
    public static function assertNull($x, BizException $e) {
        if ($x !== NULL)
            throw $e;
    }

    /**
     * 数据非 null
     * @param $x
     * @param BizException $e
     * @throws BizException
     */
    public static function assertNotNull($x, BizException $e) {
        if ($x === NULL)
            throw $e;
    }

    /**
     * 数据绝对相等
     * @param $x
     * @param $y
     * @param BizException $e
     * @throws BizException
     */
    public static function assertIsA($x, $y, BizException $e) {
        if ($x !== $y)
            throw $e;
    }

    /**
     * 数据非绝对相等
     * @param $x
     * @param $y
     * @param BizException $e
     * @throws BizException
     */
    public static function assertNotA($x, $y, BizException $e) {
        if ($x === $y)
            throw $e;
    }

    /**
     * 数据相等
     * @param $x
     * @param $y
     * @param BizException $e
     * @throws BizException
     */
    public static function assertEqual($x, $y, BizException $e) {
        if ($x != $y)
            throw $e;
    }

    /**
     * 数据不等
     * @param $x
     * @param $y
     * @param BizException $e
     * @throws BizException
     */
    public static function assertNotEqual($x, $y, BizException $e) {
        if ($x == $y)
            throw $e;
    }

}