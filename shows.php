<?php
require_once './lib/func.php';
require_once './lib/sql.php';
$currentUser='';
$roleId=6;
$s=new sql();
if(checkLogin())
{
    $currentUser=$_SESSION['user'];
    $roleId=$currentUser['roleId'];
    $sql="SELECT`name` FROM `roles` WHERE `id` ='{$roleId}'";//通过邮箱或者用户名查询
    $t=$s->select($sql);
    $role=$t['result'][0]['name'];
}
$limit='';
$limitId='';
if(!empty($_GET['id']))

{   $limitId=$_GET['id'];
    $limit=" AND s.`categoryId`={$limitId}";
}
$search='';
$search1='';
if(!empty($_POST['search']))
{
    $search1=$_POST['search'];
    $search=" HAVING  s.`title`LIKE '%{$search1}%'";
}

$sql='SELECT s.`id`, s.`title`,u.`name`as user_name, s.`user_id`, s.`categoryId`,
s. `content`, s.`create_time` FROM `show_list` AS s,users AS u WHERE s.user_id=u.id '.$limit.$search.' limit 0,10';
$t=$s->select($sql);
$shows=$t['result'];

$sql='SELECT s.`id`, s.`title`,u.`name`as user_name, s.`user_id`, s.`categoryId`,
s. `content`, s.`create_time` FROM `show_list` AS s,users AS u WHERE s.user_id=u.id '.$limit.$search.' ORDER BY `create_time` DESC limit 0,10';
$t=$s->select($sql);
$showsD=$t['result'];
$sql='SELECT * FROM `category`';
$t=$s->select($sql);
$category=empty($t['result'])?'':$t['result'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap-theme.css">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="./bootstrap/css/common.css">

    <title>webFileManage</title>
</head>
<body>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">切换导航</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                    <a rel="nofollow" class="navbar-brand" href="index.php">webFileManage</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a rel="nofollow" href="index.php">首页</a>
                        </li>
                        <li class="dropdown active">
                            <a rel="nofollow" href="#" class="dropdown-toggle" data-toggle="dropdown">经验分享<strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                                <?php foreach($category as $v):?>
                                    <li>
                                        <a rel="nofollow" href="shows.php?id=<?php echo $v['id']?>"><?php echo $v['name']?></a>
                                    </li>
                                <?php endforeach;?>
                                <li class="divider">
                                </li>
                                <li>
                                    <a rel="nofollow" href="shows.php">全部显示</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a rel="nofollow" href="#" class="dropdown-toggle" data-toggle="dropdown">资源列表<strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                                <?php foreach($category as $v):?>
                                    <li>
                                        <a rel="nofollow" href="index.php?id=<?php echo $v['id']?>"><?php echo $v['name']?></a>
                                    </li>
                                <?php endforeach;?>
                                <li class="divider">
                                </li>
                                <li>
                                    <a rel="nofollow" href="index.php">全部显示</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-left" action="shows.php<?php echo '?id='.$limitId;?>" role="search" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" placeholder="请输入搜索内容"/>
                        </div> <button type="submit" class="btn btn-default">搜索</button>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if(empty($currentUser)):?>
                            <li>
                                <a rel="nofollow" href="login.php">登录</a>
                            </li>
                            <li >
                                <a rel="nofollow" href="register.php" >注册</a>
                            </li>
                        <?php else:?>
                            <li >
                                <a rel="nofollow" href="#" ><?php echo $role." :"?></a>
                            </li>
                            <li class="dropdown">
                                <a rel="nofollow" href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $currentUser['name']?><strong class="caret"></strong></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a rel="nofollow" href="./files_upload.php">上传资源</a>
                                    </li>
                                    <li>
                                        <a rel="nofollow" href="./share.php">分享经验</a>
                                    </li>
                                    <li>
                                        <a rel="nofollow" href="./editUser.php">编辑个人资料</a>
                                    </li>
                                    <li>
                                        <a rel="nofollow" href="./showIndividual.php">个人中心</a>
                                    </li>
                                    <?php if($roleId<3): ?>
                                        <li class="divider">
                                        </li>
                                        <li>
                                            <a rel="nofollow" href="./admin/admin_index.php">后台管理</a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="divider">
                                    </li>
                                    <li>
                                        <a rel="nofollow" href="./deal/logout.php">退出</a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif;?>
                    </ul>
                </div>
            </nav>

            <div class="container">
                <div class="row clearfix">
                    <div class="col-md-12 column">
                        <div class="carousel slide" id="carousel-144502">
                            <ol class="carousel-indicators">
                                <li class="active" data-slide-to="0" data-target="#carousel-144502">
                                </li>
                                <li data-slide-to="1" data-target="#carousel-144502">
                                </li>
                                <li data-slide-to="2" data-target="#carousel-144502">
                                </li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img alt="" src="./files/images/1.jpg" />
                                    <div class="carousel-caption">
                                        <h4>
                                            跳伞
                                        </h4>
                                        <p>
                                            它以自身的惊险和挑战性，被世人誉为“勇敢者的运动                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img alt="" src="./files/images/2.jpg" / />
                                    <div class="carousel-caption">
                                        <h4>
                                            潜泳
                                        </h4>
                                        <p>
                                            潜泳是在水压加大、长时间屏气和剧烈肌肉动作的条件下进行的
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img alt="" src="./files/images/3.jpg" / />
                                    <div class="carousel-caption">
                                        <h4>
                                            高尔夫
                                        </h4>
                                        <p>
                                            高尔夫，俗称小白球，是一种室外体育运动。
                                        </p>
                                    </div>
                                </div>
                            </div> <a rel="nofollow" class="left carousel-control" href="#carousel-144502" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a> <a rel="nofollow" class="right carousel-control" href="#carousel-144502" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div>
                        <div class="tabbable" id="tabs-960611">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a rel="nofollow" href="#panel-243720" data-toggle="tab">资源列表</a>
                                </li>
                                <li>
                                    <a rel="nofollow" href="#panel-371937" data-toggle="tab">最新上传</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="panel-243720">
                                    <div class="container">
                                        <div class="row clearfix">
                                            <div class="col-md-12 column">
                                                <table class="table table1">
                                                    <thead>
                                                    <tr class="tr1">
                                                        <th>
                                                            分享名称
                                                        </th>
                                                        <th>
                                                            分享时间
                                                        </th>
                                                        <th>
                                                            分享人
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($shows as $v):?>
                                                        <?php if ($v['id']%4==1):?>
                                                            <tr class="success tr1">
                                                        <?php elseif ($v['id']%4==2):?>
                                                            <tr class="active tr1">
                                                        <?php elseif ($v['id']%4==3):?>
                                                            <tr class="warning tr1">
                                                        <?php elseif ($v['id']%4==0):?>
                                                            <tr class="danger tr1">
                                                        <?php endif;?>
                                                        <td>
                                                            <a href="showsDetail.php?showId=<?php echo $v['id'];?>"> <?php echo $v['title'];?></a>
                                                        </td>
                                                        <td>
                                                            <?php echo date('Y/m/d/H;i;s',$v['create_time']);?>
                                                        </td>
                                                        <td>
                                                            <a href="showIndividual.php?id=<?php echo $v['user_id'];?>"><?php echo $v['user_name'];?></a>
                                                        </td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                                <?php if(empty($shows)):?>
                                                <div class="row clearfix">
                                                    <div class="col-md-12 column" align="center">
                                                        <span style="font-size: large">还没有内容发布呦，快去其他板块看看吧！</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php else:?>
                                            <div class="row clearfix">
                                                <div class="col-md-12 column" align="center">
                                                    <button id='bid1' type="button" style="width:500px "  class="btn btn-default nofollow">加载更多</button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="panel-371937">
                                <div class="container">
                                    <div class="row clearfix">
                                        <div class="col-md-12 column">
                                            <table class="table table2">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        分享名称
                                                    </th>
                                                    <th>
                                                        分享时间
                                                    </th>
                                                    <th>
                                                        分享人
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </thead>
                                                <tbody>
                                                <?php foreach($showsD as $v):?>
                                                    <?php if ($v['id']%4==1):?>
                                                        <tr class="success tr2">
                                                    <?php elseif ($v['id']%4==2):?>
                                                        <tr class="active tr2">
                                                    <?php elseif ($v['id']%4==3):?>
                                                        <tr class="warning tr2">
                                                    <?php elseif ($v['id']%4==0):?>
                                                        <tr class="danger tr2">
                                                    <?php endif;?>
                                                    <td>
                                                        <a href="showsDetail.php?showId=<?php echo $v['id'];?>"> <?php echo $v['title'];?></a>
                                                    </td>
                                                    <td>
                                                        <?php echo date('Y/m/d/H;i;s',$v['create_time']);?>
                                                    </td>
                                                    <td>
                                                        <a href="showIndividual.php?id=<?php echo $v['user_id'];?>"><?php echo $v['user_name'];?></a>
                                                    </td>
                                                    </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                            <?php if(empty($showsD)):?>
                                            <div class="row clearfix">
                                                <div class="col-md-12 column" align="center">
                                                    <span style="font-size: large">还没有内容发布呦，快去其他板块看看吧！</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php else:?>
                                        <div class="row clearfix">
                                            <div class="col-md-12 column" align="center">
                                                <button id='bid2' type="button" style="width:500px "  class="btn btn-default nofollow">加载更多</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="footer">
        <div class="footerNav">
            <a rel="nofollow" href="http://www.layoutit.cn">关于我们</a> | <a rel="nofollow" href="http://www.layoutit.cn">服务条款</a> | <a rel="nofollow" href="http://www.layoutit.cn">免责声明</a> | <a rel="nofollow" href="http://www.layoutit.cn">网站地图</a> | <a rel="nofollow" href="http://www.layoutit.cn">联系我们</a>
        </div>
        <div class="copyRight">
            Copyright ©2010-2014layoutit.cn 版权所有
        </div>
    </div>
</div>
</div>
</div>
<script src="bootstrap/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
<script src="bootstrap/js/layer/layer.js"></script>
<script type="text/javascript">
    $('#bid1').click(function(){
        var num =  $('.tr1').length - 1;
        var search1='<?php echo $search1?>';
        var limit='<?php echo $limit?>';
        var id=1;
        $.post('./deal/shows.php',{number:num,search1:search1,limit:limit,id:id},function(data){
            //2ajax请求
            if(data.code == 200){
                //正常返回数据
                $('.table1').append(data.msg);
            }
            if(data.code == 400){
                alert('数据已取完');
                $('#bid1').remove();
            }
            if(data.code == 300){
                $('.table1').append(data.msg);
                $('#bid1').remove();
                alert('数据已取完');
            }
            if(data.code == 500){
                alert('数据已取完');
                $('#bid1').remove();
            }
        },'json');
    });

    $('#bid2').click(function(){
        var num =  $('.tr2').length;
        var search1='<?php echo $search1?>';
        var limit='<?php echo $limit?>';
        var id=2;
        $.post('./deal/shows.php',{number:num,search1:search1,limit:limit,id:id},function(data){
            //2ajax请求
            if(data.code == 200){
                //正常返回数据
                $('.table2').append(data.msg);
            }
            if(data.code == 400){
                alert('数据已取完');
                $('#bid2').remove();
            }
            if(data.code == 300){
                $('.table2').append(data.msg);
                alert('数据已取完');
                $('#bid2').remove();
            }
            if(data.code == 500){
                alert('数据已取完');
                $('#bid2').remove();
            }
        },'json');
    });
</script>
</html>
