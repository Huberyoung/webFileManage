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
//如果id有值，则得到该id的用户
$id=@$_GET['id']?$_GET['id']:$currentUser['id'];
$sql='SELECT * FROM `users` WHERE id='.$id;
$t=$s->select($sql);
$user=$t['result'][0];
//如果type有值，则按时间降序排列
$type=@$_GET['type']? $_GET['type']:0;
//获得类别，用于顶部遍历
$sql='SELECT * FROM `category`';
$t=$s->select($sql);
$category=empty($t['result'])?'':$t['result'];
//获得该用户的所有文件
if($type==1){
    $sql='SELECT * FROM `upload` WHERE user_id='.$user['id'].' ORDER BY create_time DESC LIMIT 0,10';
}else{
    $sql='SELECT * FROM `upload` WHERE user_id='.$user['id'].' LIMIT 0,10';
}
$t=$s->select($sql);
$uploads=$t['result'];
//获得该用户的所有分享
if($type==1){
    $sql='SELECT * FROM `show_list` WHERE user_id='.$user['id'].' ORDER BY create_time DESC LIMIT 0,10';
}else{
    $sql='SELECT * FROM `show_list` WHERE user_id='.$user['id'].'  LIMIT 0,10';
}
$t=$s->select($sql);
$shows=$t['result'];
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
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">切换导航</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a rel="nofollow" class="navbar-brand" href="index.php">webFileManage</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li >
                            <a rel="nofollow" href="index.php">首页</a>
                        </li>
                        <li class="dropdown">
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


            <div data-id="10825099" data-username="zhenxuan7536" class="dl_download dl_pdf clearfix">
                <div class="dl_download_box dl_download_l">
                    <dl class="personal_wrap" id="personal_wrap">
                        <dt style="margin: 20px"><a target="_blank" href="#"><img src="<?php echo $user['pic']?>" alt="" width="130px" height="130px"  class="head"></a>
                            <span class="vip vip_l vip_l_single"></span>
                        </dt>
                        <dd style="font-size: x-large ;margin-top: 30px;">
                            <div class="mod_person_r">
                                <div class="check_all" style="text-align: left;">
                                    <p >用户名 : <span><?php echo $user['name'];?></span></p>
                                    <p >财财币 ：<span><?php echo $user['money'];?></span></p>
                                    <p >用户积分 ：<span><?php echo ceil(intval($user['money']/3));?></span></p>
                                </div>
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="col-md-12 column">
                <div class="tabbable" id="tabs-760076">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a rel="nofollow" href="#panel-243720" data-toggle="tab">资源列表</a>
                        </li>
                        <li>
                            <a rel="nofollow" href="#panel-371937" data-toggle="tab">分享列表</a>
                        </li>
                        <li>
                            <a rel="nofollow" href="./showCollectl.php ? id=<?php echo $id;?>">收藏列表</a>
                        </li>
                        <li class="dropdown pull-right">
                            <a rel="nofollow" href="#" data-toggle="dropdown" class="dropdown-toggle">排序<strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a rel="nofollow" href="./showIndividual.php?id=<?php echo $id;?>">时间升序</a>
                                </li>
                                <li>
                                    <a rel="nofollow" href="./showIndividual.php?<?php echo $id;?> && type=1">时间降序</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="panel-243720">
                            <div class="container">
                                <div class="row clearfix">
                                    <div class="col-md-12 column">
                                        <table class="table table1">
                                            <thead>
                                            <tr>
                                                <th>
                                                    资源名称
                                                </th>
                                                <th>
                                                    资源概述
                                                </th>
                                                <th>
                                                    上传时间
                                                </th>
                                                <th>
                                                    价值
                                                </th>
                                                <?php if(($roleId<3)|| $currentUser['id']==$user['id']): ?>
                                                <th>
                                                    操作
                                                </th>
                                                <?php endif;?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($uploads as $v):?>
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
                                                    <a href="fileDetail.php?uploadId=<?php echo $v['id'];?>"> <?php echo $v['name'];?></a>
                                                </td>
                                                <td>
                                                    <a href="fileDetail.php?uploadId=<?php echo $v['id'];?>"><?php echo $v['des'];?></a>
                                                </td>
                                                <td>
                                                    <?php echo date('Y/m/d/H;i;s',$v['create_time']);?>
                                                </td>
                                                <td>
                                                    <span>财财币：</span> <span style="font-size: large ; color: red"> <?php echo $v['price'];?></span>
                                                </td>
                                                <?php if(($roleId<3)|| $currentUser['id']==$user['id']): ?>
                                                <td>
                                                    <a href="./editUpload.php?id=<?php echo $v['id'];?>"><span>编辑</span></a><span> | </span>  <a href="./deal/delete.php?uploadId=<?php echo$v['id'];?>" class="deletion"><span> 删除</span></a>
                                                </td>
                                                <?php endif;?>
                                                </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                        </table>
                                        <?php if(empty($uploads)):?>
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
                                            <?php if(($roleId<3)|| $currentUser['id']==$user['id']): ?>
                                            <th>
                                                操作
                                            </th>
                                            <?php endif;?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($shows as $v):?>
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
                                                <a href="showsDetail.php?showId=<?php echo $v['id'];?>"><?php echo $v['title'];?></a>
                                            </td>
                                            <td>
                                                <?php echo date('Y/m/d/H;i;s',$v['create_time']);?>
                                            </td>
                                            <?php if(($roleId<3)|| $currentUser['id']==$user['id']): ?>
                                            <td>
                                                <a href="./editShows.php?id=<?php echo $v['id'];?>"><span>编辑</span></a><span> | </span> <a href="./deal/delete.php?showId=<?php echo$v['id'];?>" class="deletion"><span> 删除</span></a>
                                            </td>
                                            <?php endif;?>
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
                                        <button id='bid2' type="button" style="width:500px "  class="btn btn-default nofollow">加载更多</button>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>
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
        var num =  $('.tr1').length;
        var type='<?php echo $type; ?>';
        var userId='<?php echo $user['id']; ?>';
        $.post('./deal/showIndividual',{userId:userId,number:num,type:type},function(data){
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
                $('#bid1').remove();
                alert('数据已取完');

            }
        },'json');
    });

    $('#bid2').click(function(){
        var num =  $('.tr2').length;
        var type='<?php echo $type; ?>';
        var userId='<?php echo $user['id']; ?>';
        $.post('./deal/showIndividual2',{userId:userId,number:num,type:type},function(data){
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
    $(function () {
        $('.deletion').on('click',function () {
            if(confirm('一旦删除不可恢复，还要删除?'))
            {
                window.location = $(this).attr('href');
            }
            return false;
        })
    });

</script>
</html>
