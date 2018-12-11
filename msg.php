<?php
     include_once './lib/func.php';
    // 通过urltype判断是否成功，1代表失败，2代表成功
    $type = isset($_GET['type'])&&in_array($_GET['type'], array(1,2)) ? intval($_GET['type']):2;
    $msg = isset($_GET['msg']) ? trim($_GET['msg']) : '操作成功';

    $title = $type==2 ? '操作成功':'操作失败';
    
    $url =  isset($_GET['url']) ? trim($_GET['url']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" type="text/css" href="./bootstrap/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./bootstrap/css/done.css"/>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/css/site.min.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="logo f1">
       <!--  <img src="./static/image/logo.png"> -->
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="image_center">
            <?php if ($type==2): ?>
                <span class="smile_face">:)</span>     
            <?php else: ?> 
                <span class="smile_face">:(</span>
            <?php endif;?>
        </div>
        <div class="code">
            <?php echo trim($msg);?>
        </div>
        <div class="jump">
            页面在 <strong id="time" style="color: #009f95">3</strong> 秒 后跳转
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
</body>
<script src="./bootstrap/js/jquery.min.js"></script>
<script>
    $(function () {
        var time = 3;
         var url = "<?php echo $url;?>" || null;//js读取php变量
        setInterval(function () {
            if (time > 1) {
                time--;
                console.log(time);
                $('#time').html(time);
            }
            else {
                $('#time').html(0);
                if (url) {
                    location.href = url;
                } else {
                    history.go(-1);
                }

            }
        }, 1000);

    })
</script>
</html>
