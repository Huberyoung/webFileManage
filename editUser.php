<?php
require_once './lib/captcha.php';
require_once './lib/func.php';
require_once './lib/sql.php';
if(!checkLogin())
{
    msg(1,'您没有权限访问！请先登录或注册','index.php');
}
$captchae=new Captcha(240,50);
$captchae->logic(30);
$captchae->show();

$s=new sql();
$currentUser=$_SESSION['user'];
$roleId=$currentUser['roleId'];
$sql="SELECT`name` FROM `roles` WHERE `id` ='{$roleId}'";//通过邮箱或者用户名查询
$t=$s->select($sql);
$role=$t['result'][0]['name'];
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
                    </ul>
                </div>
            </nav>
            <div class="container projects">
                <div class="projects-header page-header">
                    <span class="h3">用户信息修改</span>
                </div>
                <!--注册框-->
                <div id="register_body" class="row">
                    <div   class="col-md-6 col-md-offset-3">
                        <form class="form-horizontal" action="./deal/editUser.php" method="post" enctype="multipart/form-data" id="edit-form" >
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">用户名</label>
                                <div class="col-sm-10">
                                    <input id='name' name="name" type="text" class="form-control" placeholder="请输入用户名" value="<?php echo $currentUser['name']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-2 control-label">用户密码</label>
                                <div class="col-sm-10">
                                    <input id='password'  name="password" type="password" class="form-control" placeholder="请输入密码" value="<?php echo $currentUser['password']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputRePassword" class="col-sm-2 control-label">确认密码</label>
                                <div class="col-sm-10">
                                    <input id='repassword'  name="repassword" type="password" class="form-control" placeholder="请再次输入密码" value="<?php echo $currentUser['password']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPic" class="col-sm-2 control-label">头像</label>
                                <div class="col-sm-10">
                                    <input id='pic'  name="pic" type="file">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputDes" class="col-sm-2 control-label">个人简介</label>
                                <div class="col-sm-10">
                                    <textarea id='des' name="des" class="form-control" rows="2"  placeholder="不超过60字"><?php echo $currentUser['des']?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">请输入下方验证码</label>
                                <div class="col-sm-10">
                                    <input id='captcha'  name="captcha" type="text" class="form-control" placeholder="请输入验证码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id= 'sdf' for="inputPassword3" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                  <img id ='code' src="./files/images/captcha.png">
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
        $('#edit-form').submit(function () {
            var name = $('#name').val(),
                password = $('#password').val(),
                repassword = $('#repassword').val(),
                des= $('#des').val(),
                captcha= $('#captcha').val();
            if (name == '' || name.length <= 0) {
                layer.tips('用户名不能为空', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }
            if ( des == '' ||  des.length <= 0) {
                layer.tips('个人简介不能为空', '#des', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }
            if ( captcha!= '<?php echo strtolower($_SESSION['captcha'])?>'){
                layer.tips('请输入正确的验证码', '#captcha', {time: 2000, tips: 2});
                $('#captcha').focus();
                return false;
            }
            return true;
        })

    })
</script>
</html>