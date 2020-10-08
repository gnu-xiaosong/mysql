# mysql操作类api
依托于thinkphp开发的数据库操作类api接口,简单实用，只需要传入相应参数即可。配置好相关数据库信息。<br>
## 介绍:<br>
该数据库操作类api基于thinkphp的数据库操作的二次封装。封装了数据操作中常用的查询，更新，删除……常用的操作。支持mysql,pdo等数据库类型。只需配置相关数据录库信息，传入所要执行的常用数据库参数即可成功调用返回数据。
## 使用说明:<br>
调用url:`http://域名/index.php/api/参数`<br>
api返回类型:`array`<br>
## 目录结构:<br>
>>>application
## 数据库操作类文件<br>

## 参数配置<br>
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

```


