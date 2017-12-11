<?php
//定义命名空间 （以其父级的目录串联命名）
namespace kuangjiaming\model;
use Exception;
use PDO;
class Base{
//    定义一个私有静态变量存储实例化后的类PDO,这样才能在类内部里面自由调用。
//    为什么不用普通属性——>因为不想让构造方法里面的if判断反复执行，这样可以提高代码的运行效率，
//    这样的话只要此类new一次便有了（存储实例化后的类PDO），这样在第二次new此类就不用再次执行构造方法里if判断里面的程序，
//    从而提高了代码运行效率  (在同一个文件下多次new此类，参考下面）
//    $row=(new Model())->iud('insert into student set name="mysql"');
//    var_dump($row);
//    $roe=(new Model())->slc('select*from student');
//    var_dump($roe);
    private static $pdo=null;
    private $table;
    private $fields='';
    private $where;
    private $order='';
    private $desc='';

    /**
     * Base constructor.
     * @param $class  （system\model\Student）当前主调类的类名
     */
//    创建构造方法：
//    功能：只要所在的类用new 这一动作，它就会自动运行且运行在其他方法的最前面
    public function __construct($class){
//        echo '我是构造方法';
//        p($class);
//        获得的是一个长字符串，还不是需要的数据表名。我们得从里面获取
//        方式一：
//        因为需要在整个类当中调用，需要创建属性来存储            记得斜杠需转义
        $this->table = strtolower (ltrim (strrchr($class,'\\'),'\\'));
//        方式二：
//        通过斜杠转为数组
//        $info = explode ( '\\' , $class );
//        取出数组里面下标为2所对应的值
//		$this -> table = strtolower ( $info[ 2 ] );
//        调用方法connect()：
        $this->connect();
    }

//    连接数据库的动作
    public function  connect(){
//         此打印是用来测试
//        echo '我是构造方法';
        if(is_null (self::$pdo)) {
            try {
//                1.连接数据库
//                定义三个变量，在后面做实参
//               连接信息：驱动：mysql、主机地址：host、数据库名：dbname
                $dsn = c('database.driver') . ":host=" . c('database.host') . ";dbname=" . c('database.dbname');
//                用户名
                $username = c('database.user');
//                用户名密码
                $password = c('database.password');
//                定义一个变量接收类PDO返回的值
                self::$pdo = new PDO($dsn, $username, $password);//传参
//               2. 设置字符集
                self::$pdo->query('set names utf8');
//               3. 设置错误属性 （sql用异常，让其抛出显示在页面）
//                  意思就是设置错误模式，它里面默认的是静默错误模式（sql有错，没有提示），
//                  一共有三种 ：(静默)ERRMODE_SILENT、（警告）ERRMODE_WARNING、（语法）ERRMODE_EXCEPTION
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (Exception $exception) {
//                如果上面程序有错，执行catch里面的程序
//                获取错误信息并打印出其错误信息
                echo $exception->getMessage();
                die;
//                上面的简写（一样的，二选一）
//                die($exception->getMessage());
            }
        }

     }

    /**
     * @param $key  要查询的主键对应的序号
     * @return mixed
     */
//    根据主键获取数据库单一一条数据
    public function find($key){
//      $str='select*from student where id=2';
//      因为主键的名字也是不确定的，所以得知道主键所对应的名字。
//      调用方法getkey()并用变量接受其返回值
         $name=$this->getkey();
//        p($arr);die;
//       组建MySQL查询语句
         $str="select*from {$this->table} where {$name}={$key}";
//       调用slc（）方法，并传参
//       获得是二维数组。此方法是根据主键获取数据库单一一条数据，
//       所以结果一定是一条数据，所以可以把结果变为一维数组
         return current($this->slc($str));

     }

//   查询单一一条数据
    public function first ()
    {
//      三元表达式：如果$this->fields（默认是）null为真取其值$this->fields，反之字符串'*'。
//      $this->fields=$this->fields?$this->fields:'*';
        $this->fields = $this->fields ? : '*';
//       组建sql查询语句
        $sql  = "select {$this->fields} from {$this->table} {$this->where}";
//       调用方法slc（）并传参，且定义变量接收其返回值
        $data = $this -> slc($sql );
        //p($data);
//       获得是二维数组。此方法是根据主键获取数据库单一一条数据，
//      所以结果一定是一条数据，所以可以把结果变为一维数组
        return current ( $data );
    }

    /**
     * @param $fields 要查询的字段
     * @return $this
     */
//   查找指定列的字段s
    public function field($fields){
//     利用其属性存储传过来的参数
         $this->fields=$fields;
//      返回 $this是为了其链式调用的正常运行
         return $this;

     }

    /**
     * @param $where  where条件
     * @return $this
     */
//   spl语中的where条件
    public function  where($where){
//     利用其属性接收传过来的参数 (记得要连上where.注意他们之间的空格）
         $this->where='where '.$where;
//     返回 $this是为了其链式调用的正常运行
         return $this;
     }

    /**
     * @param $str  用于排序的条件元素
     * @return $this
     */
//   给查询的数据排序
    public function order($str){
//      $getstr=$str;
//      return $getstr;
//      在传过来的参数上连上一个逗号，
//      是为了防止用户只传一个参没加逗号（这时转数组会报错，因为转数组时以逗号做条件的）
        $newstr=$str.',';
//      将字符串 $newstr以逗号转为数组
        $arr=explode(',', $newstr);
//       return  $arr;
//      获取数组$arr的长度，在后面用于做判断
//      count($arr);
//      return count($arr);
//      但数组长度等于2时执行里面的程序，反之执行else里面的程序
        if(count($arr)==2){
//      连接的时候注意用空格隔开（遵循MySQL语句的正确格式）
            $this->order='order by '.$arr[0];

        }else{
//      调用属性$this->order存储--'order by '.$arr[0]
//      连接的时候注意用空格隔开（遵循MySQL语句的正确格式）
            $this->order='order by '.$arr[0];
//      调用属性$this->desc存储$arr[1]
            $this->desc=$arr[1];
        }
//      返回对象$this保证其链式调用的正常运行
        return $this;

    }

//   获取数据表中所有的数据
    public function getall(){
//      三元表达式：如果$this->fields（默认是）null为真取其值$this->fields，反之字符串'*'。
         $this->fields=$this->fields?$this->fields:'*';
//     组建MySQL查询语句（注意一定要用双引号，单引号不解析变量）
         $str="select {$this->fields} from {$this->table} {$this->where}  {$this->order} {$this->desc}";
//     调用方法slc()且传参  返回其结果
       return $this->slc($str);
    }

//   获取数据表中主键所对应的名称
    public function getkey(){
//       组建字符串（MySQL语句查询）
         $sql="desc {$this->table}";
//       调用方法slc（）并用变量接收其返回值（返回的是一个数组）
         $arr= $this->slc( $sql);
//       用foreach循环 遍历其返回的数组
         foreach ($arr as $k => $v){
//       注意在这里的Key、PRI的大小写，最好到打印出的数组里复制过来。
//       如果$v数组里面元素Key所对的值等于'PRI'，就执行里面的程序
             if($v['Key']=='PRI') {
//       利用变量接收数组$v里面元素Field所对应的值
                 $keyname = $v['Field'];
//       然后跳出整个循环
                 break;
             }
         }
//       返回其变量 $keyname
        return $keyname ;
    }

    /**
     * @param $sql  MySQL查询语句
     * @return mixed
     */
//   执行有结果的查询（select）
    public function slc($sql){
//        p($sql);
        try{
//             执行sql语句
            $res = self::$pdo->query($sql);
//             FETCH_ASSOC（得到的关联数组，数组下标全都为字符串下标）
//             FETCH_BOTH（即有字符串下标、又有数字下标）
//             FETCH_num (得到的索引数组，数组下标全都是数字下标)
//             将结果集取出来返回出去
            return $res->fetchAll (PDO:: FETCH_ASSOC);
        }catch (Exception $exception){
//             获取错误信息并打印出其错误信息
            die( $exception->getMessage ());
        }
    }

    /**
     * @param $sql  MySQL设置语句
     * @return mixed
     */
//   执行无结果集的SQL，返回的是受影响的条数
//     (insert,update ,delete)
    public function iud($sql){
//        p($sql);
        try{
//             执行sql语句
            $res = self::$pdo->exec($sql);
//             将结果（受影响的条数）返回出去
//            p($res);
            return  $res;
        }catch (Exception $exception){
//             如果上面程序有错，执行catch里面的程序
//             获取错误信息并打印出其错误信息
            die( $exception->getMessage ());
        }

    }

    /**
     * @param $data  数据更新后的值（$data是一个数组）
     * @return bool|mixed
     */
//    设置数据表的数据（更新数据）
    public function update($data){
//     测试：
//     return $data;
//     $str="update student set name='小小',age=18,sex='男' where id=6";
//    如果没有where条件就返回false.(调用其属性，where属性默认为null,null转为布尔值是false)
        if(!$this->where){
            return false;
        }
//     先定义一个变量，下面会用到
            $set='';
//     用foreach循环遍历其数组$data
        foreach ($data as $k=>$v){
//      如果$v是int类型则true，反之false
            if(is_int ($v)){
//      注意：.在PHP中是表示连接。
//            为什么做判断：因为MySQL表中的每行数据的类型是不一样的，
//             name char(是字符串需加引号)，age int(不需加引号)
                $set .= $k . '=' . $v . ',';
            }else{
                $set .= $k . '=' . "'$v'" . ',';
            }
        }
//       return $set;
//      因为按sql语句的语法，其字符串$set在最右边多了一个逗号，所以应将其截取掉
        $set = rtrim($set,',');
//      组建其slq语句
        $str="update {$this->table} set {$set}  {$this->where}";
//      调用方法iud（）并传参，在返回其返回值
        return $this->iud($str);
    }

//    删除指定的数据
    public function delete(){
//    如果没有where条件不允许更新，因为没where条件，会将整个表的数据删掉
//    这是一个危险动作。
//    delete from ：删除所有数据后。你在写入数据，数据的主键序号会连着删除前主键最大序号
//    truncate：删除所有数据后。你在写入数据，数据的主键序号会从0开始
        if(!$this->where){
            return false;
        }
        //$sql = "delete from student where id=1";
//      组建其slq语句
        $sql = "delete from {$this->table} {$this->where}";
//      调用方法iud（）并传参，在返回其返回值
        return $this->iud($sql);
    }

    /**
     * @param $data 写入的数据
     * @return mixed
     */
//    向数据表写入数据
    public function insert($data){
//      定义两个变量，后面会用到
        $key='';
        $values='';
//       return $data;
//      用foreach循环遍历其数组$data
        foreach ($data as $k=>$v){
//          把$k值连接起来用逗号隔开，利用变量$key存储起来
            $key.=$k.',';
//          把$值连接起来用逗号隔开，利用变量$values存储起来
            if(is_int($v)){
//          如果$v是int类型则不需加引号，反之加引号
                $values.=$v.',';
            }else{
//           注意加引号（需两成）一成解析变量
                $values.="'$v'".',';
            }
        }
//       因为按sql语句的语法，其字符串$key在最右边多了一个逗号，所以应将其截取掉
        $newkey = rtrim($key,',');
//       return  $newkey;
//       因为按sql语句的语法，其字符串 $values在最右边多了一个逗号，所以应将其截取掉
        $newvalues = rtrim($values,',');
//       return $newvalues;
//       $str="insert into student (name,age,sex)values('校长',66,'男')";
        $str="insert into {$this->table} ($newkey)values($newvalues)";
//      调用方法iud（）并传参，返回其返回值
        return $this->iud($str);

    }


}