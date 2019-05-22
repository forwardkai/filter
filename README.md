# filter-php表单参数过滤类
---

php表单参数过滤封装类;防注入,xss,空格过滤,特殊字符串过滤,强制类型转换<br> 
filter为参数过滤类,可放入任何PHP框架应用<br> 
helper_function.php为助手函数文件,可以通过框架入口文件引入或者composer自动引入file实现助手函数<br> 

##使用实例:
---
`$stu_id = filter($params['stu_id'], 'd');//第一个参数为检验字段,第二个字段为指定数据类型`<br> 
##数据类型实例:
---
`数字类型 'd'`<br> 
`字符串类型 's'`<br> 
`数组类型 'a'`<br> 
`浮点型 'f'`<br> 
`布尔类型 'b'`<br> 
