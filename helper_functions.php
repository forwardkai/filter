<?php

if (!function_exists('filter')) {
    /**
     * 参数过滤助手函数
     * @author liuhuan
     * @param null $value 过滤字段
     * @param null $type 数据类型
     * @return array
     */
    function filter($value, $type) {

        return \Filter::instance()->filter($value, $type);
    }
}
