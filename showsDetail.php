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
$sql='SELECT * FROM `category`';
$t=$s->select($sql);
$category=empty($t['result'])?'':$t['result'];

if(empty($_GET['showId'])){
    msg(1,'您没有传入正确的参数,请核对后重试1');
}
//获得展示分享的页面信息
$showId=$_GET['showId'];
$sql='SELECT  s.`user_id`,u.name, s.`categoryId`, s.`title`, s.`content`,s.`view`, s.`create_time` FROM `show_list` AS s,users AS u WHERE s.id='.$showId.' AND s.user_id=u.id';
$t=$s->select($sql);
if(empty($t['result'])){
    msg(1,'您没有传入正确的参数,请核对后重试');
}
$show=$t['result'][0];
//更新浏览次数
$sql="UPDATE `show_list` SET `view`= `view`+1 WHERE id=".$showId;
$s->query($sql);
//获得分享的文章类型
$sql='SELECT `name` FROM `category` WHERE id='.$show['categoryId'];
$t=$s->select($sql);
$categoryName=$t['result'][0]['name'];
//获得顶评论信息
$sql="SELECT s.`id`, s.`user_id`, s.`show_id`, s.parentId, s.`content`, s.`create_time`,u.`name`, u.`pic` FROM `users` AS u,`show_comment` AS s WHERE s.user_id = u.id  AND  s.`show_id`= '{$showId}' AND  s.parentId=0 GROUP BY id DESC";
$t=$s->select($sql);
$shows_comment=$t['result'];
$rows=$t['rows'];
//获得全部评论信息
$sql="SELECT s.`id`, s.`user_id`, s.`show_id`, s.parentId,s.`content`, s.`create_time`,u.`name`, u.`pic` FROM `users` AS u,`show_comment` AS s WHERE s.user_id = u.id  AND  s.`show_id`= '{$showId}' GROUP BY id DESC";
$t=$s->select($sql);
$shows_comments=$t['result'];

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
                    <div class="col-md-12 column" align="center">
                        <div class="col-md-12 column">
                            <div class="articleContent">
                                <h3 class="title">
                                   <?php echo $show['title']?>
                                </h3>
                                <div class="property">
                                    <span>文章类型： <?php echo $categoryName?></span> <span>作者： <?php echo $show['name']?></span> <span>发布时间：<?php echo date('Y-m-d H:i:s',$show['create_time']); ?></span> <span>点击数：<?php echo $show['view']?></span> <span>
                                </div>
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
                                    <div style="width: 900px">
                                        <div style="width: 100%;height: auto ;margin: 20px 0 0 20px;text-indent:2em;font-size: x-large"><?php echo $show['content'];?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a rel="nofollow" class="panel-title collapsed mytran" data-toggle="modal" data-id=0 data-parent="#panel-339804" href="#myModal" style="color: #FD482C">觉得分享的经验对您有用吗，点击这里评论两句吧</a>
                    </div>
                        <div class="col-md-12 column">
                            <div class="panel-group" id="panel-339804">
                                <div class="projects-header page-header">
                                    <h3>全部评论</h3>
                                </div>
                                <?php foreach ($shows_comment as $v):?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading recall">
                                            <div class="col-sm-12">
                                                <div class="col-sm-12">
                                                    <div class="image111"> <img class="img-circle" src="<?php echo $v['pic'];?>" alt="" width="50" height="50"></div>
                                                    <div class="file_detail_time">
                                                        <span class="h4"><?php echo $v['name'];?></span>
                                                        <span style="margin-left: 2%" class="h5"><?php echo date('Y-m-d H:i:s',$v['create_time']);?></span>
                                                        <span style="margin-left: 2%" class="h5"><?php echo "第 ".$rows." 楼";?></span>
                                                        <a href="" id='close'></a>
                                                        <div style="width: 100%;height: auto ;margin: 20px 0 0 20px;text-indent:2em"><?php echo $v['content'];?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="read_call">
                                                <?php if(($roleId<3)|| $currentUser['id']==$show['user_id'] || $currentUser['id']==$v['user_id']): ?>
                                                    <a rel="nofollow" class="panel-title collapsed deletion " data-toggle="modal"  data-parent="#panel-339804" href="./deal/showDelete.php?showId=<?php echo $v['id'];?>">删除</a>
                                                <?php endif;?>
                                                <a rel="nofollow" class="panel-title collapsed mytran" data-toggle="modal" data-id="<?php echo $v['id'];?>" data-parent="#panel-339804" href="#myModal">回复</a>
                                                <a rel="nofollow" class="panel-title collapsed" data-toggle="collapse" data-parent="#panel-339804" href="#panel-element-223583<?php echo $rows;?>">查看回复</a>
                                            </div>
                                        </div>
                                        <div id="panel-element-223583<?php echo $rows--;?>" class="panel-collapse collapse">
                                            <?php $message=0; foreach ($shows_comments as $value):?>
                                                <?php if($v['id']==$value['parentId']):$message++?>
                                                    <div class="panel-body">
                                                        <div class="col-sm-12">
                                                            <div class="col-sm-12 recall">
                                                                <div class="image111"> <img class="img-circle" src="<?php echo $value['pic'];?>" alt="" width="50" height="50"></div>
                                                                <div class="file_detail_time">
                                                                    <span class="h4"><?php echo $value['name'];?></span>
                                                                    <span style="margin-left: 2%" class="h5"><?php echo date('Y-m-d H:i:s',$value['create_time']);?></span>
                                                                    <div style="width: 100%;height: auto ;margin: 20px 0 0 20px;text-indent:2em"><?php echo $value['content'];?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                            <?php if($message==0):?>
                                                <div class="panel-body">
                                                    还没有回复内容呦，做第一个？
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                                <!-- 模态框 -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <form class="form-inline" action="./deal/showComment.php" method="post" enctype="multipart/form-data" id="comment-form">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">评论</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea id='comment' name="comment" class="form-control" rows="5" style="margin: 2%;width: 98%" placeholder="评论两句吧，记得不要超过300字呦"></textarea>
                                                    <input type="hidden"  name="parentId" />
                                                    <input type="hidden"  name="showId" />
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary" name="submit" id="submit" onclick="show(this)">发布</button>
                                                    <button type="reset" class="btn btn-default">重置</button>
                                                </div>
                                            </div>
                                        </form>
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
    $(".mytran").click(function(){
        showId=<?php echo $showId?>;
        parentid=$(this).data('id');//获取当前点击事件的id，及data-id属性的值
        $("input[name='parentId']").val(parentid);
        $("input[name='showId']").val(showId);
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
    $(function () {
        $('#comment-form').submit(function () {
            var comment = $('#comment').val();
            if (comment == '' || comment.length <= 0) {
                layer.tips('内容不能为空', '#comment', {time: 2000, tips: 2});
                $('#comment').focus();
                return false;
            }
            return true;
        })
    });
</script>
</html>
