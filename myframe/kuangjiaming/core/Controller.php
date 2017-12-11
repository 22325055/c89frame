<?php
namespace kuangjiaming\core;
//此类被APP目录里面的Index类所继承
class Controller{
//创建一个私有属性用来存储指定的跳转路径
    private $url;
    public function message($msg){
        include './view/message.php';//加载路径参考：加载了此类的文件
    }
    public function setRedirect($url=''){
        if($url){
//         程序进来了说明指定了跳转地址，
//         调用属性$url存储路径：
            $this->url="location.href='$url'";
        }else{
//          进来此说明没有给跳转地址，默认back
//          调用属性存储路径：
            $this->url  = "window.history.back()";
        }
//        返回  $this
        return $this;
    }

}