<?php
require_once './lib/func.php';
require_once './lib/sql.php';
if(!checkLogin())
{
    msg(1,'请您先登录','index.php');
}
$currentUser=$_SESSION['user'];
$s=new sql();
$roleId=$currentUser['roleId'];
$sql="SELECT`name` FROM `roles` WHERE `id` ='{$roleId}'";
$t=$s->select($sql);
$role=$t['result'][0]['name'];

unset($sql,$t);
$sql="SELECT * FROM `category`";//通过邮箱或者用户名查询
$t=$s->select($sql);
$category=$t['result'];

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
                        <li>
                            <a rel="nofollow" href="#">链接</a>
                        </li>
                        <li class="dropdown">
                            <a rel="nofollow" href="#" class="dropdown-toggle" data-toggle="dropdown">下拉菜单<strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                                <?php foreach($category as $v):?>
                                    <li>
                                        <a rel="nofollow" href="index.php?id=<?php echo $v['id']?>"><?php echo $v['name']?></a>
                                    </li>
                                <?php endforeach;?>
                                <li class="divider">
                                </li>
                                <li>
                                    <a rel="nofollow" href="index.php?id=5">其他</a>
                                </li>
                                <li class="divider">
                                </li>
                                <li>
                                    <a rel="nofollow" href="index.php">全部显示</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
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
                                <li class="divider">
                                </li>
                                <li>
                                    <a rel="nofollow" href="./admin/admin_index.php">后台管理</a>
                                </li>
                                <li class="divider">
                                </li>
                                <li>
                                    <a rel="nofollow" href="./deal/logout.php">退出</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container projects">
                <div class="projects-header page-header">
                    <span class="h3">经验分享</span>
                </div>
                <!--经验分享框-->
                <div id="register_body" class="row">
                    <div   class="col-md-6 col-md-offset-3">
                        <form class="form-horizontal" action="./deal/share.php" method="post" enctype="multipart/form-data" id="register-form" >
                            <div class="form-group">
                                <label for="inputDes" class="col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input id='name'  name="name" type="text" class="form-control" placeholder="请输入名称"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputDes" class="col-sm-2 control-label">选择类别</label>
                                <div class="col-sm-10">
                                    <p>
                                        <select class="form-control" name="category">
                                            <?php foreach($category as $v):?>
                                                <option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputDes" class="col-sm-2 control-label">分享内容</label>
                                <div class="col-sm-10">
                                    <textarea id='detail' name="detail" class="form-control" rows="8"  placeholder="不超过1000字"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-default">提交</button>
                                    <button type="submit" class="btn btn-default">重置</button>

                                </div>
                            </div>
                        </form>
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
    $(function () {
        $('#register-form').submit(function () {
            var name = $('#name').val(),
                detail= $('#detail').val();
            if (name == '' || name.length <= 0 ||  name.length >= 30) {
                layer.tips('名称规格不符合', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            if (detail == '' ||  detail.length <= 0 ||  detail.length >= 3000) {
                layer.tips('详情规格不符合', '#detail', {time: 2000, tips: 2});
                $('#email').focus();
                return false;
            }
            return true;
        })

    })
</script>
</html>
