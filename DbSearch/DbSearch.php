<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Help;
    
class DbSearch
{
      
    public function search($arr)
    {
    //公共参数:
    
    
    //状态参数
    $status=$arr["arrStatu"]["status"];
    $statu2=$arr["arrStatu"]["statu2"];
    
    //分页参数控制
    $numPage=$arr["page"]["numPage"];
    $eachPageNum=$arr["page"]["eachPageNum"];
    
    //必要参数
    $column=$arr["necessary"]["column"];
    $name=$arr["necessary"]["name"];
    $table=$arr["necessary"]["table"];
    
    //数组下标
    $item=$arr["Item"]["item"];
    $item_item=$arr["Item"]["item_item"];
    
    //where条件参数
    $getTerm=$arr["where"]["getTerm"];

    //非必要配置参数
    $getMakeSql=$arr["configure"]["getMakeSql"];
    $ifCache=$arr["configure"]["ifCache"];
    $getCacheTime=$arr["configure"]["getCacheTime"];
    $limit=$arr["configure"]["limit"];
    $getOrderColumn=$arr["configure"]["getOrderColumn"];//排序字段
    $getOrderRule=$arr["configure"]["getOrderRule"];//排序规则
   
   
   //参数转化
    $makeSql=!empty($getMakeSql)?$getMakeSql:false;//是否生成sql语句,默认false
    $cache=!empty($ifCache)?$ifCache:false;   //缓存设置，默认不
    $cacheTime=!empty($getCacheTime)?$getCacheTime:60;   //缓存时间 单位秒 默认60秒
    $limitNum=!empty($limit)?$limit:' ';//默认不限制查询数量
    $orderRule=!empty($getOrderRule)?$getOrderRule:'asc';//默认排序规则为asc
    $orderColoumn=!empty($getOrderColumn)?$getOrderColumn:'id';//默认排序字段名为id
    
    //程序提示语
    $hint="相关状态值提示:<br>一级状态值status:<br>0:模糊查询<br>1:数据处理类  (大小比较)<br>2:链式条件查询<br>3:数据统计处理类(二级状态值statu2)<br>[<br>0:获取字段最大值<br>1:获取某字段的最小值<br>2:获取某字段的平均值<br>3:获取某字段的总和<br>4:个数统计<br>]<br>4:分页查询 (常用于文章等)<br>5:精准查询<br>";
           
    switch($status){ 
    /*数组参量
    array(
    $name,//查询变量参数
    $item, //数组下标控制变量
    $item_item,//第二数组下标控制变量 
    );
    */
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
          '_'.$name
          );
          
          
    // echo    $NaNum=!empty($item)?$item:2;   //参数控制查询类型[array类型] 默认2
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
      //  echo $LkNum=isset($item_item)?$item_item:1;   //参数控制查询类型array类型
           $result=Db::name($table)->where($column,$reLike[$item_item],$reName[$item])->fetchSql($makeSql)->cache($cache,$cacheTime)->limit($limitNum)->select();
               return $result;
    break;
          
          /*under is successful !*/
          
          
          
          
    case 1://数据处理类  (大小比较)
    /*
    *每次处理$Chnum次
    *@param:$users   结果集
    */
    /*
    array(
    $name,//查询字段变量
    $item,//数组下标控制
    $column //操作字段变量
    );
    */
    
    $reName=array(
    ">",
    ">=",
    "<",
    "<=",
    "="
    );
  //  $DaNum=!empty($item)?$item:0;
    $result=Db::name($table)->where($column,$reName[$item],(integer)$name)->fetchSql($makeSql)->limit($limitNum)->order($orderColoumn,$orderRule)->cache($cache,$cacheTime)->select();
    return $result;
    break;
          /*under is successful !*/
          
    
    
    
    
    
   
    case 2: //链式条件查询
      //链式条件数组封装 添加字段封装数组$if
    /*  array(
      $getTerm,//条件封装 多维数组
      //默认单条件查询
      $column,//所要查询字段
      $name //查询字段的内容
      );
      */

    dump( $if=!empty($getTerm)?$getTerm:array(
    $column=>$name
      ));
    return  $result=Db::name($table)->where($if)->fetchSql($makeSql)->limit($limitNum)->order($orderColoumn,$orderRule)->cache($cache,$cacheTime)->select();
    break;
               /*under is successful !*/
  
  
  
    case 3://数据统计处理类
    /*
    *$is:参数控制
    *默认执行count()函数
    */
 /*   array(
    $is //是否强制转换(true,false)boolea类型 默认否
    $getStatu //参数控制
    $column, //所操作的字段
    
    );
    */
    $statu=!empty($statu2)?$statu2:0;  //默认为0
    $transport=!empty($is)?true:false;//默认关闭
       switch($statu){
       case 0://获取字段最大值 当最大值不为数值是，是否关闭强制转换
           /*
            *@param:$transport  是否强制转换(true,false)
            *
            */
          if($transport){
           $data=Db::name($table)->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->max($column,!$transport);
           }else{
           $data=Db::name($table)->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->max($column);
           //where条件嵌套
         //  $data=Db::name($table)->where([])->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->max($column);
           }
       break;
       
       case 1://获取某字段的最小值
           /*
            *@param:$transport  是否强制转换(true,false)
            *
            */
          if($transport){
           $data=Db::name($table)->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->min($column,!$transport);
           }else{
           $data=Db::name($table)->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->min($column);
           //where条件嵌套
         //  $data=Db::name($table)->where([])->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->min($column);
           }
       break;
       
       
       
       
       case 2://获取某字段的平均值
             $data=Db::name($table)->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->avg($column);
             //加入where条件
            // $data=Db::name($table)->where([])->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->avg($column);
       break;
        
        
        
       case 3://获取某字段的总和
             $data=Db::name($table)->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->sum($column);
             //加入where条件
            // $data=Db::name($table)->where([])->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->sum($column);
       break;
       
       
      case 4://默认执行
            //个数统计
           $data=Db::name($table)->fetchSql($makeSql)->limit($limitNum)->cache($cache,$cacheTime)->count($column);
       break;
       default:
           $data="该状态值不合法！请重新输入！".$hint;
           break;
                   }
       return $data;
    break;
    
                   /*under is successful !*/
    
    
    
    
    case 4:  //分页查询 (常用于文章等)
   /*参数说明:
    *@param:$numPage  从第几页开始查询
    *@param:$eachPageNum  每页查询数量
    *@param:
    */
    /*
    array(
    $numPage,//从第几页开始 默认1
    $eachPageNum, //读取几条数据  默认10条
    );
    */
    
    $pages=array(
    'numPage'=>!empty($numPage)?$numPage:1, //默认从第一页
    'eachPageNum'=>!empty($eachPageNum)?$eachPageNum:10 //默认查询10条
    );
     $result=Db::name($table)->page($pages['numPage'],$pages['eachPageNum'])->fetchSql($makeSql)->cache($cache,$cacheTime)->select(); 
    return $result;
    break;
                   /*under is successful !*/
    
    
          
    case 5:
      /*  array(
    $column,//所要查找的字段
    $name  //在该字段中所要查找的内容
    );
    */
        //精准查询.默认查询方式
          $result=Db::name($table)->where($column,$name)->cache($cache,$cacheTime)->fetchSql($makeSql)->limit($limitNum)->select(); //find()查询单个数据
          return $result;
     break;   

     default:
      return   $data="该状态值不合法！<br>请选择指定状态值<br>".$hint;
           
         break;
    }
    }
    
}