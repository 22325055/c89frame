<?php
//定义命名空间
namespace kuangjiaming\view;
//执行此类是为了让不同方法里面定义的变量（$var='变量'）能够在整个类当中调用
class Base{
    private $data = [];//存储变量
    private $file = '';//模板文件
    /**
     * 显示模板文件
     */
    public function make(){
//     常量在启动类被定义
//     常量不受局部的限制，所以在这里能使用在启动类被定义的常量
//     CONTROLLER常量是控制器类 类名首字母是大写而目录在这都是小写，所以要进行小写的转换
//     调用属性$this->file存储路径
        $this->file =  '../app/'.MODULE.'/view/'.strtolower (CONTROLLER).'/'.ACTION.'.' . c('view.suffix');
//      return $this;是为了让链式调用正常运行和 __toString ()方法能运行
        return $this;
    }

    /**
     * 分配变量，
     */
//    创建一个公共的普通方法with($var = [])给形参赋默认值为空数组
    public function with($var =[]){
//      echo '我是with方法里面的输出';
//      p($var) ;die ;
//        p(c('view.suffix'));
//     调用属性$this->data存储$var
        $this->data = $var;
//      return $this;是为了让链式调用正常运行和 __toString ()方法能运行
        return $this;
    }

    public function __toString ()
    {
//       echo '我是__toString方法';
        //p($this->data);die;
        //将键名变为变量名字，将键值变为变量值
        extract ($this->data);
//        p(extract ($this->data));//1
//        经过extract之后，就会产生变量
//        产生变量名叫什么：看调用With时候给的变量名字是什么
//        p($data);
        //p($a);
        //die;
//        加载模板文件
//       为了防止调用时候只调用with，不调用make出现的报错
//        你在调用时候View::with(),就会出现报错
        if($this->file){
//
//           如果$this->file不为空则加载$this->file，
//          注意$this->file存储的是一个路径
            include $this->file;
        }
//   __toString() 方法一定要 return 一个字符串才行，不然报错（记得举例练习）
        return '';
    }

}
