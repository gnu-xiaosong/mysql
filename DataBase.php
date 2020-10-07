<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Help;
class DataBase
{

    //数据库查询操作方法
    public function search($status,$table,$column,$name)
    {
     /*
     *
     *参数说明:
     *@param:$status  [必要] 查询类型状态参数 int类型
     *@param:$table    [必要]  所查询的表名
     *@param:$column   [必要]  查询字段名
     *@param:$name    [必要]    所要查询内容
     *@param:$limit   [非必要]  所要查询数量 默认不限制
     *@param:$getOrderColoumn    [非必要]  要进行排序的字段名
     *@param:$getOrderRule     [非必要]    排序规则:desc  asc 默认不进行排序
     *@param:$ifCache     [非必要]   boolean类型  是否设置查询缓存
     *@param:$getCacheTime   [非必要]  查询缓存数据时间 单位:秒。默认60
     *@param:$getMakeSql   [非必要]  boolean类型  是否返回值生成sql语句  默认false
     *@param:$getTerm  [非必要]  链式条件查询$getTerm中为(字段=>查询值)也可插入其他查询，例如like  默认为[$column=>$name]
     *@param:返回类型:array
     */
    $makeSql=isset($getMakeSql)?$getMakeSql:false;//是否生成sql语句,默认false
    $cache=isset($ifCache)?$ifCache:false;   //缓存设置，默认不
    $cacheTime=isset($getCacheTime)?$getCacheTime:60;   //缓存时间 单位秒 默认60秒
    $limitNum=isset($limit)?$limit:' ';//默认不限制查询数量
    $orderRule=isset($getOrderRule)?$getOrderRule:'asc';//默认排序规则为asc
    $orderColoumn=isset($getOrderColumn)?$getOrderColumn:'id';//默认排序字段名为id
    $order=array(
    'order'=>isset($getOrderRule)?[$orderColoumn=>$orderRule]:''
    );
   
    //根据传入状态值判断查询类型
    
    switch($status){ 
    case 0: //模糊查询
    /*
     *like参数:
    '%a'    //以a结尾的数据
     'a%'    //以a开头的数据
     '%a%'    //含有a的数据
     ‘_a_’    //三位且中间字母是a的
      '_a'    //两位且结尾字母是a的
     'a_'    //两位且开头字母是a的
     *@param:$reName  $name参数封装
     *@param:Nanum   数组选择控制参数 int类型
     */   
          $reName=array(
          "%".$name,    
          $name."%",     
          "%".$name."%",
          '_'.$name.'_',
          $name.'_',
          '_'.$name,
          );
         $NaNum=2;   //参数控制查询类型[array类型]
      /*
      *
      *模糊查询字段封装:
      *@param: $reLike
      *@param: not like 表不含
                 like  表含
      *@param:Lknum   数组选择控制参数 int类型
      */
      $reLike=array(
      'not like',   
      'like'       
      );
        $LkNum=1;   //参数控制查询类型array类型
          $result=Db::name($table)->where($column,$reLike[$LkNum],$reName[$NaNum])->fetchSql($makeSql)->cache($cache,$cacheTime)->order($order['order'])->limit($limitNum)->select();
          return $result;
    break;
          
          
    case 1://数据处理类  (大小比较)
    /*
    *每次处理$Chnum次
    *@param:$users   结果集
    */
    $reName=array(
    ">",
    ">=",
    "<",
    "<=",
    "=",
    "!="
    );
    $DaNum=3;
    $result=Db::name($table)->where($column,$reName[$DaNum],(integer)$name)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->select();
    return $result;
    break;
    
    //有问题
    case 2: //查询JSON类型字段 （info字段为json类型）
      $result=Db::name($table)
	           ->where($column,$name)
	     /*      ->fetchSql($makeSql)
	           ->order($order['order'])
	           ->cache($cache,$cacheTime)
	           */
	           ->select();
	     return $result;
    break;
      
      
    case 3: //链式条件查询
      //链式条件数组封装 添加字段封装数组$if
      
      $if=isset($getTerm)?$getTerm:array(
      $column=>$name
      );
    return  $result=Db::name($table)->where($if)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->select();
    break;
          
          
          
          
    case 4: //多表查询 利用原生查询
      $result=Db::table('xs_bank1')

                     ->alias('a')

                     ->join('xs_bank2 w','a.ID= w.id')

                     ->where("a.question",'like',"中国")
                     ->fetchSql($makeSql)
                     ->order($order['order'])
                     ->cache($cache,$cacheTime)
                     ->limit($limitNum)
                     ->select();
          return $result;
    break;
          
          
    case 5://数据统计处理类
    /*
    *$is:参数控制
    *默认执行count()函数
    */
    $statu=10;
    $is=1;
    $transport=isset($is)?true:false;
       switch($statu){
       case 1://获取字段最大值 当最大值不为数值是，是否关闭强制转换
           /*
            *@param:$transport  是否强制转换(true,false)
            *
            */
          if($transport){
           $data=Db::name($table)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->max($column,!$transport);
           }else{
           $data=Db::name($table)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->max($column);
           //where条件嵌套
         //  $data=Db::name($table)->where([])->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->max($column);
           }
       break;
       case 2://获取某字段的最小值
           /*
            *@param:$transport  是否强制转换(true,false)
            *
            */
          if($transport){
           $data=Db::name($table)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->min($column,!$transport);
           }else{
           $data=Db::name($table)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->min($column);
           //where条件嵌套
         //  $data=Db::name($table)->where([])->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->min($column);
           }
       break;
       
       case 3://获取某字段的平均值
             $data=Db::name($table)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->avg($column);
             //加入where条件
            // $data=Db::name($table)->where([])->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->avg($column);
       break;
        
       case 4://获取某字段的总和
             $data=Db::name($table)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->sum($column);
             //加入where条件
            // $data=Db::name($table)->where([])->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->sum($column);
       break;
      default://默认执行
            //个数统计
           $data=Db::name($table)->fetchSql($makeSql)->order($order['order'])->limit($limitNum)->cache($cache,$cacheTime)->count($column);
       break;
                   }
       return $data;
    break;
    
    
    
    case 6:  //分页查询 (常用于文章等)
   /*参数说明:
    *@param:$numPage  从第几页开始查询
    *@param:$eachPageNum  每页查询数量
    *@param:
    */
    $pages=array(
    'numPage'=>isset($numPage)?$numPage:1, //默认从第一页
    'eachPageNum'=>isset($numPage)?$$eachPageNum:10 //默认查询10条
    );
       $result=Db::name($table)->page($pages['numPage'],$pages['eachPageNum'])->fetchSql($makeSql)->order($order['order'])->cache($cache,$cacheTime)->select(); 
    return $result;
    break;
    default:
        //精准查询.默认查询方式
          $result=Db::name($table)->where($column,$name)->cache($cache,$cacheTime)->fetchSql($makeSql)->limit($limitNum)->select(); //find()查询单个数据
          return $result;
    break;
    }
    }
    
    
    
    
    
    //时间查询
    public function timeSearch($status,$time,$table)
    {
    /*
     *
     *参数说明:
     *@param:$status  [必要] 查询类型状态参数 int类型
     *@param:$time  [必要] 时间字段名 
     *@param:$table  [必要] 表名 
     *@param:$getMakeSql [非必要] 是否生成sql语句，boolean类型，默认关闭
     *@param:$getItem  [必要] 时间查询类型控制，int或者varchar类型
     *@param:$hours [查询小时时必要] int类型  用于case 2
     *@param:$start_time 开始时间  单位hours
     *@param:$end_time  结束时间   单位hours
     */
    $makeSql=isset($getMakeSql)?$getMakeSql:false;//是否生成sql语句,默认false
    $item=isset($getItem)?$getItem:0; //默认 为0 ;时间查询类型控制，int或者varchar类型
     
    switch($status){
    
    case 0://时间比较查询
    /*
     *
     *参数说明:
     *@param:$status  [必要] 查询类型状态参数 int类型
     *@param:$table  [必要] 表名 
     *@param:$getTime [必要] 传入的时间值 格式:2020-10-05
     *@param:$getItem  [必要] 比较符号参数
     *@param:$headTime  [必要] between区间值前
     *@param:$footTime  [必要] between区间值后
     */
     $compareSignal=array(
      '>'=>'>',
      '>='=>'>=',
      '<'=>'<',
      '<='=>'<=',
      '='=>'='
     );
      $result=Db::table($table)->whereTime($getTime ,$compareSignal[$item],$getTime)->select();
    break;
    
    
    case 1://时间区间查询
     $compareSignal=array(
      '~'=>'between',//某个时间区间
      '!~'=>'not between',//不再某个时间区间
      );
      $item=isset($getItem)?$getItem:0; //默认选择>符号比较
      $result=Db::table($table)->whereBetweenTime($getTime,$headTime,$footTime)->select();
    break;
    
    default://查询当天、本周、本月和今年的时间，(默认)
    /*
     *@param:$end_time和$start_time用于查询当前时间为准的前$start_time后$end_time时间内的数据
     *@param:$start_time开始时间  单位hours
     *@param:$end_time  结束时间   单位hours
     */
    $itemTime=array(
     'd'=>'today',  //今天
     'yd'=>'yesterday', //昨天
     'w'=>'week', //本周
     'lw'=>'last week', //上周
     'm'=>'month', //本月
     'lm'=>'last month', //上月
     'y'=>'year', //今年
     'ly'=>'last year',//去年
     'h'=>$hours.' '.'hours',//$hours小时内
     'sta'=>$getTime=$start_time,
     'end'=>$itemTime[$item]=$end_time
    );
    $setTime=$itemTime[$item];
    $result=Db::table($table)
              ->whereTime($getTime,$setTime)
              ->select();
    break;
    }
    return $result;
    }
    
    
    
    //添加数据
    public function insertData(){
    /*
     *
     *参数说明:
     *@param:$getData [必要] 插入数组 array类型
     格式:$getData=[column1=>data1,column2=>data2,column3=>data3]  (插入单条数据 $status选择0)
          $getData=[
          ['foo' => 'bar', 'bar' => 'foo'],
          ['foo' => 'bar1', 'bar' => 'foo1'],
          ['foo' => 'bar2', 'bar' => 'foo2']
          …
          ]  (插入多条数据)
     *@param:$strict 是否抛出异常(即当字段不存在时剔除不存在字段，从而不抛出异常) boolean类型 默认抛出false
     *@param:$status 状态控制参数
     *@param:$table   插入表名
     */
    $ifStrict=isset($strict)?$strict:false;//默认抛出异常
    $data=$getData; //数组赋值转换
    switch($status){
    case 0://插入单条数据
      $result=Db::table($table)->strict($ifStrict)->insert($data);
    break;
 
    default://插入数据
    $result=Db::table($table)->strict($ifStrict)->insertAll($data);
    break;
    }
    return $result;
}


  //更新操作
  public function update(){
    /*
     *
     *参数说明:
     *@param:$status 状态控制参数
     *@param:$table   插入表名
     *@param:$column  索引字段
     *@param:$value  索引字段的值
     *@param:$setColumn  指定修改的字段名
     *@param:$setValue  指定修改的字段名的值
     *@param:$getData  要更新的字段及值 array类型:['name' => 'thinkphp',…]  不传入，默认修改本索性字段及值
     */
     $data=isset($getData)?$getData:array($setColumn=>$setValue );//默认修改本更新单条数据
     switch($status){
     case 0://更新单条数据
        $result=Db::table($table)
                  ->where($column,$value)
                  ->data([$setColumn=>$setValue])
                  ->update();
     break;
     
     case 1://更新字段值
     $result=Db::table($table)
                ->where($column,$value)
                ->setField($setColumn,$setValue);
                 break;
     default:
        $result=Db::table($table )
                  ->where($column,$value)
                  ->data($data)
                  ->update();
     break;
     }
     return $result;
  }
  
  
  //删除操作
  public function deleData(){
    /*
     *
     *参数说明:delete 方法返回影响数据的条数，没有删除返回 0
     *@param:$status 状态控制参数
     *@param:$table   插入表名
     *@param:$getDataID   删除数据的主键id
     
     *@param:$delColumn   指定要删除数据的字段索引{字段名}
     *@param:$delValue  指定要删除数据的字段索引的值{字段名对应的值}
     *@param:$getItem  指定数组索引参数 int 或 varchar类型
     *@param:$table   插入表名
     $getDataID :int 或 array类型
     eg:int 1;
        array [1,2,3]
     */
     $item=isset($getItem)?$getItem:0;//默认精准delete
     $if=array(
     'delSet'=>$delColumn.','.$delValue,  //精准删除
     'delLike1'=>$delColumn.','.'like'.','.'%'.$delValue,//模糊删除以$delValue开头的
     'delLike2'=>$delColumn.','.'like'.','.'%'.$delValue.'%',//模糊删除包含$delValue的
     'delLike3'=>$delColumn.','.'like'.','.$delValue.'%',//模糊删除以$delValue结尾的
     'delNotLike1'=>$delColumn.','.'not like'.','.'%'.$delValue,//模糊删除以$delValue开头的
     'delNotLike2'=>$delColumn.','.'not like'.','.'%'.$delValue.'%',//模糊删除包含$delValue的
     'delNotLike3'=>$delColumn.','.'not like'.','.$delValue.'%',//模糊删除以$delValue结尾的
     'delComp1'=>$delColumn.','.'>'.','.$delValue,
     'delComp2'=>$delColumn.','.'>='.','.$delValue,
     'delComp3'=>$delColumn.','.'='.','.$delValue,
     'delComp4'=>$delColumn.','.'<'.','.$delValue,
     'delComp5'=>$delColumn.','.'<='.','.$delValue,
     'delComp6'=>$delColumn.','.'!='.','.$delValue
     );
     switch($status){
     
     case 0:// 根据主键删除
          $data=$getDataID;
     $result=Db::table($table )->delete($data);
     break;
     
     case 1:// 条件删除
     $result=Db::table($table)->where($if[$item])->delete();
     break;
     default:
     
     break;
     }
     return $result;

  }

}
