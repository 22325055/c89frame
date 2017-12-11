<?php
//定义命名空间  （用文件的父级目录串联起来）
namespace kuangjiaming\core;
//创建类 BOOt(一般首字大写，类名与文件名保持一致)
class Boot{
//创建公共静态方法run()，其实普通方法也行。
//用静态方法的原因：静态的方法在调用的时候好输入，节约输入时间
//静态：  kuangjiaming\core\Boot::run();
//普通:   (new kuangjiaming\core\Boot())->run();
    public static function run(){
        self::ReportErrors();
//1.执行初始化动作（即调用方法init）
        self::init();
//随便打印几个文字进行测试，看看程序是否正常运行
//        echo '首页';

//通过get参数来控制访问的模块、控制器、方法。按以前的方法是传三个参数，
//现在换种方法：?s=home/article/index (只传一个长字符串)
//   if判断：如果$_GET数组存在元素s，则会返回布尔值ture，
//   反之false。  （isset()返回的是布尔值）
        if(isset($_GET['s'])){
//       1.接收以get方式穿过来的数据
            $s=$_GET['s'];
//         测试程序是否运行
//           p($s) ;
//        2.将$s转为数组（传过来的是字符串）
            $arr=explode('/',$s);
//        3.声明三个变量并都赋值
            $m = $arr[ 0 ];//模块
            $c = ucfirst ( $arr[ 1 ] );//控制器类,首字母大写，因为他是类名字
            $a = $arr[ 2 ];//方法
       }else{
//        不存在get参数是给予默认值
            $m = 'home';//模块
            $c = 'Index';//控制器类
            $a = 'index';//方法
        }
//        定义三个常量，为了在后面方便使用。
//        以define定义的常量可以不受命名空间、类的限制，
//        以const定义的常量受 类、命名空间 的限制。
           define('MODULE',$m);
           define('CONTROLLER',$c);
           define('ACTION',$a);
        $controller = "\app\\{$m}\controller\\{$c}";//斜杠须转义，不然他会转义括弧
//        ( new $controller ) -> $a ();
//        下面这句话，就详单与上面这句
//        new $controller这个类，调用$a,并且把该函数的第二个参数作为$a方法的参数
        echo call_user_func_array ( [ new $controller , $a ] , [] );
//        接下来，我们构建MVC中的C就是controler
//        接下来在app/home/controller/Index.php文件中进行测试
    }

//创建静态方法init（）用来放置文件头部该（必须）做的工作
    public static function init(){
//        1.声明头部
        header('Content-type:text/html;charset=utf8');
//        2.设置时区
        date_default_timezone_set('PRC');
//        3.开启session
//        这里用了短路写法（简便）
//        如果已经有session_id()说明session已开启。
//        反之则说明session没开启。然后执行后面——开启session。
//        注意：重复开启session会导致报错
        session_id()||session_start();
    }
//  友好报错的方法（composer，这是一个插件）
    public static function ReportErrors(){
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}

