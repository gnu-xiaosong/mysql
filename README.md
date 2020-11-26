# mysql操作类api
依托于thinkphp开发的数据库操作类api接口,简单实用，配置好相关数据库信息,只需要传入相应参数即可。并且遵循相关开源程序。<br>
## 开发者<br>
* 开发者:小松科技
* myBlog:http://www.xskj.store
* 时间:2020-10-07
* QQ:1829134124
* 介绍:一名计算机爱好者的在校大学生
## 官方文档<br>
[官方文档](http://www.xskj.store)
## 介绍:<br>
该数据库操作类api基于thinkphp的数据库操作的二次封装。封装了数据操作中常用的查询，更新，删除……常用的操作。支持mysql,pdo等数据库类型。只需配置相关数据录库信息，传入所要执行的常用数据库参数即可成功调用返回数据。
## 使用说明:<br>
调用url:`http://域名/index.php/api/参数`<br>
api返回类型:`array`<br>
## 目录结构:<br>
>>>application
## 数据库操作类文件<br>

## 参数配置<br>
#### 一级状态参数
 |status|含义|
  |---|---|
  |0|模糊查询|
   |1|数据处理类|
|2|链式条件查询|
|3|数据统计处理|
|4|分页查询|
|其他|默认精准查询|
#### 二级级状态参数(数据统计处理类)
|statu2|含义|
  |---|---|
  |0|获取字段最大值|
   |1|获取某字段的最小值|
|2|获取某字段的平均值|
|3|获取某字段的总和|
|4|个数统计|
#### 模糊查询item参数
|item|含义|
  |---|---|
  |0|左包含|
   |1|右包含|
|2|全包含|
|3|三个字符中间一个为$name[一个_代表一个字符]|
|4|三个字符左一个为$name|
|5|三个字符右一个为$name|

### 参数配置代码:
```php
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
    "getTerm"=>!empty($getTerm)?$getTerm:''   //多维数组[一维也可]  格式:array([字段1=>值1],[字段2=>值2]…) 如果为空默认采用         {$column=>$name}一维单条件查询
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
```
## api版本介绍<br>
请求url:`http://你的ip/index.php/api/type?dataType= &value= `
<br>
### 参数介绍<br>

|参数|必要性|说明|
  |---|---|---|
  |type|必要值|请求的类型，支持get和post请求.当参数为get时，则为get请求.并且不区分大小写.当为其他字符时默认为post请求|
   |dataType|非必要值|不指定则默认返回数据类型为json|
|value|必要值|索引值,不指定默认索引"China"|




`type`: `必要值``请求的类型，支持get和post请求.当参数为get时，则为get请求``并且不区分大小写``当为其他字符时默认为post请求`<br>
`dataType`:`返回的数据类型``非必要值``不指定则默认返回数据类型为json`
`可选值:``array 和 json``注意:非array字符串默认为json类型`<br>
`value`:`必要值``搜索值``不指定默认搜索配置"China"`
#### 测试地址<br>
return `array`:`http://api.xskj.store/index.php/api/get?dataType=array&value=测试`[返回array格式数据](http://api.xskj.store/index.php/api/get?dataType=array&value=测试)<br>
return `json`:`http://api.xskj.store/index.php/api/get?dataType=json&value=测试`[返回json格式数据](http://api.xskj.store/index.php/api/get?dataType=json&value=测试)<br>
## 添加时间查询类文件
<br>
## 主文件index.php部分代码:
```php
    //时间查询测试

    public function api1(){
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
```
###  参数配置
#### 参数配置对应功能提示
`还未总结发布`
<br>
#### 参数代码:
```
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

        "getOrderColumn"=>"ID",        //排序字段 $orderColoum
```
<br>

## 代码贡献<br>
欢迎大家贡献相关代码！共同维护该项目



