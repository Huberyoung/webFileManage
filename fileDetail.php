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

if(empty($_GET['uploadId'])){
    msg(1,'您没有传入正确的参数,请核对后重试');
}
$uploadId=$_GET['uploadId'];
$sql='SELECT * FROM `upload` WHERE id='.$uploadId;
$t=$s->select($sql);
if(empty($t['result'])){
    msg(1,'您没有传入正确的参数,请核对后重试');
}
//更新浏览次数
$upload=$t['result'][0];
$sql="UPDATE `upload` SET `view`= `view`+1 WHERE id=".$upload['id'];
$s->query($sql);
//获得用户信息
$sql='SELECT * FROM `users` WHERE id='.$upload['user_id'];
$t=$s->select($sql);
$user=$t['result'][0];
//获得当前页面的上传者发布的经验分享列表
$sql='SELECT * FROM `show_list` WHERE user_id='.$upload['user_id'].' ORDER BY `create_time` DESC limit 0,5';
$t=$s->select($sql);
$shows=$t['result'];
//获得当前页面的类别
$sql='SELECT `name` FROM `category` WHERE id='.$upload['category_id'];
$t=$s->select($sql);
$categoryName=$t['result'][0]['name'];
//获得当前页面的评论信息
$sql="SELECT c.`id`,c.user_id,c.`parentId`, c.`content`, c.`create_time`, u.`name`, u.`pic` FROM `users` AS u, `upload_comment` AS c WHERE c.user_id = u.id AND `upload_id`= '{$uploadId}'AND parentId=0 ";
$t=$s->select($sql);
$upload_comment=$t['result'];
$rows=$t['rows'];
//获得当前页面的子评论信息
$sql="SELECT c.`id`,c.user_id,c.`parentId`, c.`content`, c.`create_time`, u.`name`, u.`pic` FROM `users` AS u, `upload_comment` AS c WHERE c.user_id = u.id AND `upload_id`= '{$uploadId}' GROUP BY id DESC";
$t=$s->select($sql);
$upload_comment1=$t['result'];
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
                        <li class="active">
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
            <div class="container">
                <div class="row clearfix">
                    <div class="col-md-8 column">
                        <div class="download_top_wrap clearfix">
                            <div class="download_top_t">
                                <dl class="download_dl" id="download_dl">
                                    <dt><img src="./files/images/c.jpg" width="100px" height="100px" >
                                        <div class="star_box"></div>
                                        <i class="user_grade"></i>
                                    </dt>
                                    <dd>
                                        <h2><span><?php echo $upload['name'];?></span></h2>
                                        <div class="download_b">
                                        <div class="dl_b">
                                            <label>文档类型:<a href="index.php?id=<?php echo $upload['category_id'];?>"><span style="margin-left: 10px;"><?php echo $categoryName;?></span></a></label>
                                            <strong class="size_box">
                                                <span style="margin-left: 50px;">上传时间: </span><span class="h5" style="margin-left: 10px;"><?php echo date('Y-m-d H:i:s',$upload['create_time']);?></span>
                                                <span style="margin-left: 50px;">浏览次数: </span><span class="h5" style="margin-left: 10px;"><?php echo $upload['view'];?></span>
                                            </strong>
                                        </div>
                                            <span class="pre_description"><?php echo $upload['detail'];?></span>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div data-id="10825099" data-username="zhenxuan7536" class="dl_download dl_pdf clearfix">
                            <div class="dl_download_box dl_download_l">
                                <label style="margin-left: 50px">所需:<span style="font-size: 40px;color: #FD482C"><?php echo $upload['price']?></span>财财币</label>
                                <a type="button" class="transmoney money" data-toggle="modal" data-id="<?php echo $v['id'];?>" data-target="#transmoney" ><button style="width: 150px;height:60px; margin-left: 20px" >立即下载</button></a>
                                <a href="./deal/collect.php<?php echo '? uploadId='.$uploadId;?>" id="favorite" class="dl_func"><button style="width: 150px;height:60px; margin-left: 20px" ><span>收藏</span></button></a>
                                <?php if(($roleId<3)|| $currentUser['id']==$user['id']): ?>
                               <a href="./deal/delete.php ? type=2 & uploadId=<?php echo$uploadId;?>" class="direct_download deletion" target="_self" data-bind-login="true"> <button style="width: 150px;height:60px; margin-left: 20px" >删除</button></a>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="modal fade" id="transmoney" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <form class="form-inline" action="./deal/down.php" method="post" enctype="multipart/form-data">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">购买</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>下载需要 <span id="money" style="color: #3c3c3c;font-size: x-large"></span> 财财币</p>
                                            <input type="hidden"  name="uploadId" />
                                            <input type="hidden"  name="payee" />
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" name="submit" id="submit" onclick="show(this)">确认支付</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="panel-group" id="panel-339804">
                        <form class="form-horizontal" action="./deal/fileDetail.php<?php echo '? uploadId='.$uploadId;?>" method="post" enctype="multipart/form-data" id="comment-form" >
                            <div class="form-group">
                                <div class="col-sm-11">
                                    <textarea id='comment' name="comment" class="form-control" rows="5" style="margin: 2% 0 0 2%" placeholder="评论两句吧，记得不要超过300字呦"></textarea>
                                </div>
                                <button type="reset" class="btn btn-default" style="margin-top: 60px">重置</button>
                                <button type="submit" class="btn btn-primary btn-default">发布</button>
                            </div>
                        </form>
                        </div>
                        <div class="col-md-12 column">
                            <div class="panel-group" id="panel-339804">
                                <div class="projects-header page-header">
                                    <h3>全部评论</h3>
                                </div>
                                <?php foreach ($upload_comment as $v):?>
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
                                            <?php if(($roleId<3)|| $currentUser['id']==$user['id'] || $currentUser['id']==$v['user_id']): ?>
                                            <a rel="nofollow" class="panel-title collapsed deletion " data-toggle="modal"  data-parent="#panel-339804" href="./deal/delete.php?commentId=<?php echo $v['id'];?>">删除</a>
                                            <?php endif;?>
                                            <a rel="nofollow" class="panel-title collapsed mytran" data-toggle="modal" data-id="<?php echo $v['id'];?>" data-parent="#panel-339804" href="#myModal">回复</a>
                                            <a rel="nofollow" class="panel-title collapsed" data-toggle="collapse" data-parent="#panel-339804" href="#panel-element-223583<?php echo $rows;?>">查看回复</a>
                                        </div>
                                    </div>
                                    <div id="panel-element-223583<?php echo $rows--;?>" class="panel-collapse collapse">
                                        <?php $message=0; foreach ($upload_comment1 as $value):?>
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
                                        <form class="form-inline" action="./deal/fileDetail2.php" method="post" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">评论</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea id='comment' name="comment" class="form-control" rows="5" style="margin: 2%;width: 98%" placeholder="评论两句吧，记得不要超过300字呦"></textarea>
                                                    <input type="hidden"  name="parentId" />
                                                    <input type="hidden"  name="uploadId" />
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
                    <div class="col-md-4 column">
                        <div class="mod_personal" data-mod="popu_53">
                            <dl class="personal_wrap" id="personal_wrap">
                                <dt style="margin: 20px"><a target="_blank" href="#"><img src="<?php echo $user['pic']?>" alt="" width="130px" height="130px"  class="head"></a>
                                    <span class="vip vip_l vip_l_single"></span>
                                </dt>
                                <dd style="font-size: x-large ;margin-top: 30px;">
                                    <div class="mod_person_r">
                                        <div class="check_all" style="text-align: left;">
                                            <span><?php echo $user['name'];?></span>
                                            <p >用户积分：<span><?php echo ceil(intval($user['money']/3));?></span></p>
                                            <a href="./showIndividual.php" target="_blank" class="check_all_btn">查看TA的主页</a>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <div class="col-md-12 column">
                                <div class="carousel slide" id="carousel-144502">
                                    <div class="col-md-12 column">
                                        <div class="carousel slide" id="carousel-833122">
                                            <ol class="carousel-indicators">
                                                <li class="active" data-slide-to="0" data-target="#carousel-833122">
                                                </li>
                                                <li data-slide-to="1" data-target="#carousel-833122">
                                                </li>
                                                <li data-slide-to="2" data-target="#carousel-833122">
                                                </li>
                                            </ol>
                                            <div class="carousel-inner">
                                                <div class="item active">
                                                    <img alt="" src="./files/images/1.jpg" />
                                                </div>
                                                <div class="item">
                                                    <img alt="" src="./files/images/2.jpg" />
                                                </div>
                                                <div class="item">
                                                    <img alt="" src="./files/images/3.jpg" />
                                                </div>
                                                <a rel="nofollow" class="left carousel-control" href="#carousel-833122" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
                                                <a rel="nofollow" class="right carousel-control" href="#carousel-833122" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 column" style="margin-top: 50px">
                                <ul class="infoList">
                                    <?php foreach ($shows as $v):?>
                                    <li>
                                        <a rel="nofollow" title="<?php echo $v['title'];?>" href="showsDetail.php?showId=<?php echo $v['id'];?>"><?php echo $v['title'];?></a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
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
        uploadId=<?php echo $uploadId?>;
        parentid=$(this).data('id');//获取当前点击事件的id，及data-id属性的值
        $("input[name='parentId']").val(parentid);
        $("input[name='uploadId']").val(uploadId)

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
    $(".money").click(function(){
       var uploadId=<?php echo $upload['id']?>;
       var payee=<?php echo $user['id']?>;
       var money=<?php echo $upload['price']?>;
        $("input[name='uploadId']").val(uploadId);
        $("input[name='payee']").val(payee);
        if(money==''){
            money=0;
        }
        var span=document.getElementById("money");
        span.innerHTML=money;

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
