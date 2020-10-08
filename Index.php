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
    $table="bank1";
    $column="question";
    //实例化数据库搜索类
    $mysql=new DbSearch();               





/*相关状态值提示:
一级状态值status:
0:模糊查询----->param-------$item----------meaning
           "%".$name       0        左包含
           $name."%"      1          右包含
          "%".$name."%"    2          全包含
            '_'.$name.'_'    3          三个字符中间一个为$name[一个_代表一个字符]
            $name.'_'      4          三个字符左一个为$name
            '_'.$name       5          三个字符右一个为$name
            
1:数据处理类 (大小比较)
2:链式条件查询
3:数据统计处理类(二级状态值statu2)
[
0:获取字段最大值
1:获取某字段的最小值
2:获取某字段的平均值
3:获取某字段的总和
4:个数统计
]
4:分页查询 (常用于文章等)
5:精准查询
*/
    //搜索参数配置
    $arr=array(
    //状态控制参数
    "arrStatu"=>array(
         "status"=>0, //一级状态参数
         "statu2"=>4//二级状态参数
     ),
    "necessary"=>array(
        "table"=>$table,       //[查询]表名
        "column"=>$column, //必要字段    $column
        "name"=>$qu        //搜索值$name
    ),
    
    "where"=>array(         //查询条件数组参数
    "getTerm"=>!empty($getTerm)?$getTerm:''   //多维数组[一维也可]  格式:array([字段1=>值1],[字段2=>值2]…) 如果为空默认采用{$column=>$name}一维单条件查询
    ),
    
    //数组下标控制参数数组
    "Item"=>array(
        "item"=>2,  //数组一级下标参数$item
        "item_item"=>1  //数组二级下标参数$item_item
    ),
    
    "page"=>array(
        "numPage"=>1,  //分页查询开始行 int类型
        "eachPageNum"=>20 //从开始行开始每页查询多少个数据
     ),
    "configure"=>array(
        "getMakeSql"=>false,  //是否生成sql语句 boolean类型
        "ifCache"=> false,   //是否开启缓存查询 boolean累类型
        "getCacheTime"=>6,      //开启缓存查询的时间 int类型 单位秒
        "limit"=>7,      //查询数目限制 不开启 int类型
        "getOrderColumn"=>"ID",//排序字段 $orderColoumn 默认id字段    $column=>$name
        "getOrderRule"=> "desc" //排序规则$orderRule
    )
    );
    
    echo $qu;
    
    //修改对应数组参数
   // $arr["name"]="中国";
  
  //调用函数
   $data=$mysql->search($arr);
   //打印输出
    dump($data);
    }

}