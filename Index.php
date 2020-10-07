<?php
namespace app\index\controller;
use think\Controller;
/**
*导入数据库操作类库:不用引入文件，类库文件就在同级目录下，直接实例化即可
*/
class Index extends Controller
{
    //接口api1
    public function api($qu)   //$qu为url中传入的参数
    {
    $status=5;
    $table="bank3";
    $column="question";
    //实例化数据库操作类
    $mysql=new DataBase();
    
    
  dump($mysql->search($status,$table,$column,$qu));
    
    }

}