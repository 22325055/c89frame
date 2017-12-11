<?php
//这是入口文件 index
//第一步加载autoload.php 文件
//vendor目录的生成——在终端输入composer dump 敲回车（注意只能打开当前一个目录）
//生成vendor的目录之前需要在composer.json,
//手动加入autoload这一项。autoload里面有两个元素：
//一：files——>要加载的文件（自动），
//二：psr4  加载的类（自动）
//利用composer自动加载文件和类库
require '../vendor/autoload.php';
//使用require加载文件，但加载路径出错就会抛出异常并终止后面的程序
//includ加载，加载路径出错会报警告性错误后面程序继续运行
//调用启用类中的run方法
\kuangjiaming\core\Boot::run();




