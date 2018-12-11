<?php
require_once './lib/captcha.php';
require_once './lib/func.php';
require_once './lib/sql.php';
if(checkLogin())
{
    msg(2,'您已登录，无需重复登录','index.php');
}
$captchae=new Captcha(240,50);
$captchae->string(4);
$captchae->show();
$s=new sql();
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
                        <li>
                            <a rel="nofollow" href="login.php">登录</a>
                        </li>
                        <li >
                            <a rel="nofollow" href="register.php" >注册</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container projects">
                <div class="projects-header page-header">
                    <h3>登录</h3>
                </div>
                <!--注册框-->
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <form class="form-horizontal" action="./deal/login.php" method="post" enctype="multipart/form-data" id="login-form" >
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">账号</label>
                                <div class="col-sm-10">
                                    <input id='name' name="name" type="text" class="form-control" placeholder="请输入用户名或者邮箱">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-2 control-label">用户密码</label>
                                <div class="col-sm-10">
                                    <input id='password'  name="password" type="password" class="form-control" placeholder="请输入密码">
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
                                    <button type="submit" class="btn btn-primary btn-default">登录</button>
                                    <button type="reset" class="btn btn-default">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="footer1" class="login_footer">
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
        $('#login-form').submit(function () {
            var name = $('#name').val(),
                password = $('#password').val(),
                captcha= $('#captcha').val();

            if (name == '' || name.length <= 0) {
                layer.tips('账号不能为空', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }
            if ( captcha!= '<?php echo strtolower($_SESSION['captcha'])?>' && captcha != '<?php echo $_SESSION['captcha']?>'){
                layer.tips('请输入正确的验证码', '#captcha', {time: 2000, tips: 2});
                $('#captcha').focus();
                return false;
            }
            return true;
        })

    })
</script>
</html>