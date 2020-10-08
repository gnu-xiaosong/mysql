<?php
namespace app\index\controller;
use think\Controller;
use think\helper;
class Index extends Controller
{
    //接口api1
    public function api($type)   
    {
    
    //请求url
   // http://ip/index.php/api/$type?dataType=请求数据类型&name=搜索内容
   
    //判断请求类型
    if(strtolower($type)=="get"){
    //请求为get请求
    $dataType=input("get.dataType");
    $name=input("get.name");
    }else if(strtolower($type)=="post"){
    //请求为post请求
    $dataType=input("post.dataType");
    $name=input("post.name");
    }
    //接收数据
    
    $table="bank3";
    $column="question";
/************************相关状态值提示:****************************************************

**********************************************************************************************
状态值*一级状态值status:
**********************************************************************************************
       |                   param-------$item----------meaning
       |               "%".$name       0        左包含
       |                $name."%"      1          右包含
 0     | 模糊查询 -> "%".$name."%"    2          全包含
       |                 '_'.$name.'_'    3          三个字符中间一个为$name[一个_代表一个字符]
       |                  $name.'_'      4          三个字符左一个为$name
       |                 '_'.$name       5          三个字符右一个为$name
**********************************************************************************************
1      |     数据处理类 (大小比较)
**********************************************************************************************
2      |      链式条件查询
**********************************************************************************************
3      |      数据统计处理类(二级状态值statu2)

[
***************************
0:获取字段最大值
***************************
1:获取某字段的最小值
***************************
2:获取某字段的平均值
***************************
3:获取某字段的总和
***************************
4:个数统计
***************************
]
**********************************************************************************************
4      |      分页查询 (常用于文章等)
**********************************************************************************************
5      |      精准查询
**********************************************************************************************/

    //搜索参数配置
    $arr=array(
    
    "arrStatu"=>array(
         "status"=>0,                 //一级状态参数
         "statu2"=>4                 //二级状态参数
     ),
     
    "necessary"=>array(
        "table"=>$table,              //[查询]表名
        "column"=>$column,         //必要字段    $column
        "name"=>isset($name)?$name:"中国"                //搜索值$name
    ),
    
    "where"=>array(                  //查询条件数组参数
    "getTerm"=>!empty($getTerm)?$getTerm:''   //多维数组[一维也可]  格式:array([字段1=>值1],[字段2=>值2]…) 如果为空默认采用{$column=>$name}一维单条件查询
    ),
   
    "Item"=>array(
        "item"=>2,                    //数组一级下标参数$item
        "item_item"=>1               //数组二级下标参数$item_item
    ),
    
    "page"=>array(
        "numPage"=>1,                //分页查询开始行 int类型
        "eachPageNum"=>10          //从开始行开始每页查询多少个数据
     ),
     
    "configure"=>array(
        "getMakeSql"=>false,          //是否生成sql语句 boolean类型
        "ifCache"=> false,              //是否开启缓存查询 boolean累类型
        "getCacheTime"=>6,            //开启缓存查询的时间 int类型 单位秒
        "limit"=>4,                      //查询数目限制 不开启 int类型
        "transport"=>false,              //字段值不为数值时，是否强制转换成数值，boolean类型(true,false)
        "getOrderColumn"=>"ID",        //排序字段 $orderColoumn 默认id字段    $column=>$name
        "getOrderRule"=> "desc"        //排序规则$orderRule
    )
    );

    //实例化数据库搜索类
    $mysql=new DbSearch();     
    $data=$mysql->search($arr);     
    
   //判断输出的数据类型
   if(strtolower($dataType)=="array"){
   //请求数据类型为array时
     dump($data);
   }else{
   //请求数据为json时
  echo $this->translate($data);
   }
   
    }
    
    //数据类型转换函数
    public function translate($data){
    /*
    *@param:$data  传入数据  array和json  
    *@param:$getCode  是否对中文编码  默认不编码
    *@param:$getAssoc   解码json为什么类型数据  true为数组  false为object  默认为array
    */
    $code=JSON_UNESCAPED_UNICODE;//默认对中文不编码
    $assoc=true;//默认解码json数据为数组,为false为object数据
    //数据检测类型
    if(is_array($data)){
    //传入数据类型为数组时
    return json_encode($data,$code);
    }else{
    //传入数据类型为json时
    return json_decode($data,$assoc);
    }
    }
    
    
    
    

}