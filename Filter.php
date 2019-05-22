<?php
/**
 * 参数过滤
 * @author liuhuan
 * Created by PhpStorm.
 * User: liuhuan04
 * Date: 2019/5/21
 * Time: 10:58
 */

class Filter
{
    /**
     * 对象实例
     * @author liuhuan
     */
    protected static $instance;
    /**
     * filter实例
     * @author liuhuan
     */
    protected $filter = null;

    /**
     * 构造函数
     * @author liuhuan
     * @access protected
     * @param array $options 参数
     */
    protected function __construct($options = [])
    {
        foreach ($options as $name => $item) {
            if (property_exists($this, $name)) {
                $this->$name = $item;
            }
        }
        if (is_null($this->filter)) {
            $this->filter = $this->getFilter();
        }
    }

    /**
     * 初始化
     * @author liuhuan
     * @access public
     * @param array $options 参数
     * @return
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }
    /**
     * 过滤方法
     * @author liuhuan
     * @access public
     * @param null $value 过滤字段
     * @param null $type 过滤类型
     * @return
     */
    public function filter($value, $type=''){

        // 解析过滤器
        $filter = $this->filter;
        if (is_array($value)) {
            array_walk_recursive($value, [$this, 'filterValue'], $filter);
            reset($value);
        } else {
            $this->filterValue($value, '', $filter);
        }

        if ( '' !== $type) {
            // 强制类型转换
            $this->typeCast($value, $type);
        }
        return $value;
    }

    /**
     * 获取当前的过滤规则
     * @author liuhuan
     * @access public
     * @return
     */
    protected function getFilter()
    {
        $filter = [
            'trim',
            'htmlspecialchars',
            'strip_tags',
        ];
        //如果默认没有开启扩展，增加addslashes过滤
        if( false === get_magic_quotes_gpc() ){
            array_push($filter,'addslashes');
        }

        //过滤时候的默认值
        array_push($filter,'');
        return $this->filter = $filter;
    }

    /**
     * 递归过滤给定的值
     * @author liuhuan
     * @param null $value 过滤元素
     * @param null $key 键值
     * @param null $filters 过滤方法+默认值
     * @return mixed
     */
    private function filterValue(&$value, $key, $filters)
    {
        $default = array_pop($filters);

        foreach ($filters as $filter) {
            if (is_callable($filter)) {
                // 调用函数或者方法过滤
                $value = call_user_func($filter, $value);
            } elseif (is_scalar($value)) {
                if (false !== strpos($filter, '/')) {
                    // 正则过滤
                    if (!preg_match($filter, $value)) {
                        // 匹配不成功返回默认值
                        $value = $default;
                        break;
                    }
                } elseif (!empty($filter)) {
                    // filter函数不存在时, 则使用filter_var进行过滤
                    // filter为非整形值时, 调用filter_id取得过滤id
                    $value = filter_var($value, is_int($filter) ? $filter : filter_id($filter));
                    if (false === $value) {
                        $value = $default;
                        break;
                    }
                }
            }
        }
        return $value;
    }

    /**
     * 强制类型转换
     * @author liuhuan
     * @param string $data data
     * @param string $type type
     * @return mixed
     */
    private function typeCast(&$data, $type)
    {
        switch (strtolower($type)) {
            // 数组
            case 'a':
                $data = (array) $data;
                break;
            // 数字
            case 'd':
                $data = (int) $data;
                break;
            // 浮点
            case 'f':
                $data = (float) $data;
                break;
            // 布尔
            case 'b':
                $data = (boolean) $data;
                break;
            // 字符串
            case 's':
            default:
                if (is_scalar($data)) {
                    $data = (string) $data;
                } else {
                    throw new \InvalidArgumentException('variable type error：' . gettype($data));
                }
        }
    }
}