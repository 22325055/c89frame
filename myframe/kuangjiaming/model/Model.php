<?php
// 定义命名空间
namespace kuangjiaming\model;
class Model{
//为什么要写两种__call（）、__callStatic（），
//原因就是避免调用方式的不同—— $this->  和 静态调用
//__call()方法：在调用当前类的普通方法不存在时此方法自动运行
    public function __call($name, $arguments)
    {
// 用静态方式调用静态方法runparse（）|注意前面一定要加return
        return self ::runParse ( $name, $arguments  );
    }

//__callStatic()方法：在调用当前类的静态方法不存在时此方法自动运行
    public static function __callStatic($name, $arguments)
    {
// 用静态方式调用静态方法runparse（）|注意前面一定要加return
       return self ::runParse ( $name, $arguments );
    }

//（结合上面） 创建一个被调用的方法（静态的在调用的时候好输写）
    public static function runparse($name, $arguments  ){

//        return (new Base())->$name($arguments[0] );
//        实例化Base，调用方法$name、传参$arguments[0]  |注意前面一定要加return
//       此写法和上面的写法的功能是一样的（建议用下面这种写法）
//       return (new Base())->$name($arguments[0]);
//       return call_user_func_array([new Base,$name],[$arguments[0]]);
//       注意call_user_func_array（）能把所传参数（数组）里面的元素取出。
//       会把多维数组降一维（三变二）！！！这一步次想了蛮久
         $class=get_called_class();
//        获取当前主调类的类名  获取这个是为了取作为查询的表名，
//       （因为我们是按其数据库里面的表的表名做的文件名，且文件名和其里面的类名是一样的名字）
//        p($class);//system\model\Student
//        get_called_class()——获取当前主调类的类名
//        在把得到的结果通过传参传到类Base里面的构造方法里面
         return call_user_func_array([new Base( $class),$name],$arguments);

    }

}