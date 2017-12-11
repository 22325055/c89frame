<?php
//声明命名空间——以他的父级目录窜起命名
namespace app\home\controller;
//创建类 （类名与文件名保持一致利于后面其他文件的加载）
use kuangjiaming\core\Controller;
use kuangjiaming\model\Model;
use kuangjiaming\view\Base;
use system\model\Student;

//类Index 继承类 Controller
class Index extends Controller {
    public function index(){
//        include '../app/home/view/index/index.php';
//        echo '首页';
//        $str="select*from student";
//        $str=['一维数组','ER维数组'];
//        $arr=  (new Model())->slc($str) ;
//        p($arr);//测试用的
//        echo (new Base)->with()->make();
//        $data='456';
//        return  (new Base)->with(compact ('data'))->make();
//        *****测试模型中方法*****
//        1.根据主键查询单一一个数据
//        因为Student类在目录system里面，所以要在composer.json文件里面进行设置，
//        "system\\":"system\\"，设置完成后再执行composer dump命令
//        $data=Student::find(2) ;
//        p((new Student())->find(2));
//        p((new Student())->getall());
//        p((new Student())-> field('age')->getall());
//        p((new Student())-> field('age')->where('id=0')->getall());
//        p((new Student())-> field('age')->where("id=2")->first());
          $data = [
            	'age'=>18,
            	'name'=>'你好',
            	'sex'=>'男',
            ];
//        p((new Student())->where('id=7')->update($data));
//        p((new Student())->where('id=17')->delete());
//        p((new Student())->insert($data));
//        p((new Student())->order());
//        p((new Student())->getall());
        p((new Student())->field('age')->where("age>10")->order('age,desc')->getall());

    }

    public function add(){
//        $this->setRedirect ()->message('添加成功');
//        $this->setRedirect ('?s=member/mine/index')->message('添加成功');
//        封装一个生成url的函数u
//        调用方法setRedirect（当前类继承了类Controller），
//        u() 函数在助手函数库里被定义
        $this->setRedirect (u('article/index'))->message('添加成功');


    }

}


