<?php
/*
*@开发者:小松科技
*@QQ:1829134124
*@开发文档:
*@myBlog:http://www.xskj.store
*@请勿删除还注释内容，保留作者姓名，尊重别人的劳动成果！谢谢！
*/
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Help;  

class TimeSearch extends Controller
{
    //时间查询
    public function timeSearch($arr)
    {
    /*
     *
     *参数说明:
     *@param:$status  [必要] 查询类型状态参数 int类型
     *@param:$time_column  [必要] 时间字段名 
     *@param:$table  [必要] 表名 
     *@param:$getMakeSql [非必要] 是否生成sql语句，boolean类型，默认关闭
     *@param:$getItem  [必要] 时间查询类型控制，int或者varchar类型
     *@param:$hours [查询小时时必要] int类型  用于case 2
     *@param:$start_time 开始时间  单位hours
     *@param:$getTime  操作时间  
     *@param:$end_time  结束时间   单位hours
     *@param:$limitNum  限制查询数量参数变量   这里默认最大限制1000 (为了防止php超过它所能申请的最大内存(这里也可以在php.ini中修改memory_limit = 256M;扩大一倍或更多)
     */
     //配置参量
     
     $status=$arr["necessary"]["status"];               //状态控制参量
     $table=$arr["necessary"]["table"];                  //表名
     $time_column=$arr["necessary"]["time_column"];  //时间字段名
     
     $item=$arr["options"]["item"];                  //数组下标控制参量
     $time=$arr["options"]["time"];                   //时间
     $hours=$arr["options"]["hours"];                   //小时变量 int类型
     
     $headTime=$arr["interval"]["headTime"];
     $footTime=$arr["interval"]["footTime"];
     //额外配置参数
    $getMakeSql=$arr["configure"]["getMakeSql"];
    $ifCache=$arr["configure"]["ifCache"];
    $getCacheTime=$arr["configure"]["getCacheTime"];
    $limit=$arr["configure"]["limit"];
    $getOrderColumn=$arr["configure"]["getOrderColumn"];//排序字段
    $getOrderRule=$arr["configure"]["getOrderRule"];//排序规则

    //参数封装
    $getTime=!empty(strtotime($time))?strtotime($time):data("y-m-d"); //操作时间
    $makeSql=!empty($getMakeSql)?$getMakeSql:false;//是否生成sql语句,默认false
    $cache=!empty($ifCache)?$ifCache:false;   //缓存设置，默认不
    $cacheTime=!empty($getCacheTime)?$getCacheTime:60;   //缓存时间 单位秒 默认60秒
    $limitNum=!empty($limit)?$limit:0;//默认不限制查询数量
    $orderRule=!empty($getOrderRule)?$getOrderRule:'asc';//默认排序规则为asc
    $orderColoumn=!empty($getOrderColumn)?$getOrderColumn:'id';//默认排序字段名为id
    
  
    switch($status){
    case 0:
    /*
     *@topic:时间比较查询
     *参数说明:
     *@param:$getTime [必要] 传入的时间值 格式:2020-10-05
     *@param:$time_column  [必要] 时间字段
     */
     $compareSignal=array(
      '>',
      '>=',
      '<',
      '<='
     );
   $result=Db::table($table)
             ->whereTime($time_column,$compareSignal[$item],$getTime)
             ->fetchSql($makeSql)
             ->order($orderColoumn,$orderRule)
             ->cache($cache,$cacheTime)
             ->limit($limitNum)
             ->select();
    break;
    
  
    case 1:
    /*
     *@topic:时间区间查询
     *参数说明:
     *@param:$headTime [必要] 开始时间变量 格式:2020-10-05
     *@param:$footTime  [必要] 结束时间变量
     *@param:$time_column [必要] 时间字段
     */
     $compareSignal=array(
      'between time',          //某个时间区间
      'not between time'      //不再某个时间区间
      );
          $result=Db::table($table)
                 ->where($time_column,$compareSignal[$item], [strtolower($headTime), strtolower($footTime)])
                 ->fetchSql($makeSql)
                 ->order($orderColoumn,$orderRule)
                 ->cache($cache,$cacheTime)
                 ->limit($limitNum)
                 ->select();
    break;
      

    default:
    /*
     *@topic:查询当天、本周、本月和今年的时间，(默认)
     *@param:$time_column 时间字段名
     *@param:$hours 小时   单位hours 主要用于查询该$hours小时内的数据
     */
    $itemTime=array(
     'today',               //今天
     'yesterday',           //昨天
     'week',                //本周
     'last week',           //上周
     'month',               //本月
     'last month',          //上月
     'year',                 //今年
     'last year',            //去年
     '-'.$hours.' '.'hours'    //$hours小时内 int类型
    );
    $result=Db::table($table)
              ->whereTime($time_column,$itemTime[$item])
              ->limit($limitNum)
              ->fetchSql($makeSql)
              ->order($orderColoumn,$orderRule)
              ->cache($cache,$cacheTime)
              ->select();
    break;
    }
   return $result;
    }
    
    
    
}