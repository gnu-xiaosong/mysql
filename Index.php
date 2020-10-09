<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    //接口api1
    public function api($qu)   
    {
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
         "statu2"=>0                 //二级状态参数
     ),
     
    "necessary"=>array(
        "table"=>$table,              //[查询]表名
        "column"=>$column,         //必要字段    $column
        "name"=>$qu                //搜索值$name
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
    echo $this->translate($data);
   
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
  
    
    //时间查询测试
    public function api1(){
   /* 'today',               //今天
     'yesterday',           //昨天
     'week',                //本周
     'last week',           //上周
     'month',               //本月
     'last month',          //上月
     'year',                 //今年
     'last year',            //去年
     '-'.$hours.' '.'hours'    //$hours小时内 int类型
     */
    //参数数组封装
     $arr=array(
     //necessary options(必须参数配置)
     "necessary"=>array(
         "status"=>2,           //状态控制值参数  int类型  默认0
         "table"=>"xs_bank3",  //表名(全名)    varchar类型  默认
         "time_column"=>"update_time", //时间字段名
     ),
     
     //able options(可选参数配置)
     "options"=>array(
         "time"=>"2020-10-04",    //时间变量
         "hours"=>2,             //小时变量 int类型  常用于查询几小时内的数据 默认2小时内
         "item"=>8            //数组下标控制参数 int类型 默认0
     ),
     
     //time interval options(时间区间选项)
     "interval"=>array(
     "headTime"=>"2020-04-23",   //start time
     "footTime"=>"2020-10-04"     //end time
     ),
     
     //configure(配置参数)
     "configure"=>array(
        "getMakeSql"=>false,          //是否生成sql语句 boolean类型
        "ifCache"=> false,              //是否开启缓存查询 boolean累类型
        "getCacheTime"=>6,            //开启缓存查询的时间 int类型 单位秒
        "limit"=>100,                      //查询数目限制 不开启 int类型
        "getOrderColumn"=>"ID",        //排序字段 $orderColoumn 默认id字段    $column=>$name
        "getOrderRule"=> "asc"        //排序规则$orderRule
      )
     );
     
     //实例化一个TimeSearch类
    $mysql=new TimeSearch();
    //调用方法
    $data=$mysql->timeSearch($arr);
    //返回数据类型数组转化为json类型
    $is_type=false;
    echo $is_type?$this->translate($data):dump($data);
    
    
    }
 
}