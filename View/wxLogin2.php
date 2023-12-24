<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>chatGPT登录</title>
    <meta name="description" content="particles.js is a lightweight JavaScript library for creating particles.">
    <meta name="author" content="Vincent Garreau" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" media="screen" href="../src/login/css/style.css">
    <link rel="stylesheet" type="text/css" href="../src/login/css/reset.css"/>
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
</head>
<body>

<div id="particles-js">
    <div class="login">
        <!--<div class="login-top">
            登录
        </div>-->
        <div class="login-center clearfix" style="text-align: center;padding-top: 15px">
            <img src="../src/imgs/wx_gzh.jpg ">

            <div id="login-tips"></div>
<!--            <div class="login-center-img"><img src="../src/login/img/name.png"/></div>-->
            <!--<div class="login-center-input">
                <input type="text" name="username" value="" placeholder="请输入您的用户名" onfocus="this.placeholder=''" onblur="this.placeholder='请输入您的用户名'"/>
                <div class="login-center-input-text">用户名</div>
            </div>-->
        </div>
        <div class="login-center clearfix">
            <div class="login-center-img"><img src="../src/login/img/password.png"/></div>
            <div class="login-center-input" style="display: flex">
                <input type="text" name="password" value="" placeholder="请输入验证码" onfocus="this.placeholder=''" onblur="this.placeholder='请输入您获取的验证码'"/>
                <div class="login-center-input-text">登录验证码</div>
<!--                <button class="layui-btn layui-btn-sm layui-btn-normal" onclick="sendCode()" style="border-radius: 4px">发送验证码</button>-->
            </div>
        </div>
        <div class="login-button" onclick="login()">
            登陆
        </div>
<!--        <div class="login-center"></div>-->
    </div>
    <div class="sk-rotating-plane"></div>
</div>

<!-- scripts -->
<script src="../src/js/gq.js?a=1"></script>
<script src="../src/layui/src/layui.js"></script>
<script src="../src/login/js/particles.min.js"></script>
<script src="../src/login/js/app.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    // isLogin()
    //设置登录的扫码提示
    setLoginTips();

    function isLogin(){
        let token = getToken();
        if (token){
            window.location.href = './chatGpt.php';
        }
    }

    //放公共文件不知道为什么，微信自带浏览器报错
    function getRandom(min, max){
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    // isPc();

    function setLoginTips(){
        let leftText = '';
        if (isPc()){
            leftText = '扫码进入公众号后';
        }else{
            leftText = '长按扫码进入公众号后';
        }

        let randNum = getRandom(1,10000);

        // let html = leftText + '，发送消息 <span id="username">'+randNum+' </span>获取验证码进行登录';
        let html2 = '您此次登录码为 <span id="username">'+randNum+' </span>' + leftText + '发送 ' + randNum+ ', 获取验证码进行登录';
        // document.getElementById('login-tips').innerHTML = html
        $('#login-tips').html(html2);
    }

    layui.use('layer', function(){
        var layer = layui.layer;

    });
    function sendCode(){
        let username = $('input[name=username]').val();
        let password = $('input[name=password]').val();
        $.ajax({
            url:makeRequestUrl('code','send'),
            type:'get',
            data:{username: username},
            success:function (res) {
                layer.msg(res.msg);
                console.log(res)
            }
        })
    }

    get();
    function get(field = 'invite'){
        let params = window.location.href.split('?')[1];
        if (!params){
            return '';
        }
        let value = params.split('=')[1];
        if (field == 'invite'){
            console.log(value)
            return value;
        }
        return '';
    }

    function login(){
        let username = $('#username').text().trim();
        let password = $('input[name=password]').val().trim();
        let invite_uid = get();

        $.ajax({
            url:makeRequestUrl('login','wxLogin'),
            type:'get',
            data:{username: username, password: password, invite: invite_uid},
            success:function (res) {
                if (res.ok){
                    let token = res.obj.token;
                    let userid = res.obj.userid;
                    saveToken(token);
                    saveUserid(userid);
                    console.log(res)
                    window.location.href = './chatGpt.php';
                }
                layer.msg(res.msg);
                console.log(res)
            }
        })
    }

    function hasClass(elem, cls) {
        cls = cls || '';
        if (cls.replace(/\s/g, '').length == 0) return false; //当cls没有参数时，返回false
        return new RegExp(' ' + cls + ' ').test(' ' + elem.className + ' ');
    }

    function addClass(ele, cls) {
        if (!hasClass(ele, cls)) {
            ele.className = ele.className == '' ? cls : ele.className + ' ' + cls;
        }
    }

    function removeClass(ele, cls) {
        if (hasClass(ele, cls)) {
            var newClass = ' ' + ele.className.replace(/[\t\r\n]/g, '') + ' ';
            while (newClass.indexOf(' ' + cls + ' ') >= 0) {
                newClass = newClass.replace(' ' + cls + ' ', ' ');
            }
            ele.className = newClass.replace(/^\s+|\s+$/g, '');
        }
    }
/*
    document.querySelector(".login-button").onclick = function(){
        addClass(document.querySelector(".login"), "active")

        let username = $('input[name=username]').val();
        let password = $('input[name=password]').val();
        $.ajax({
            url:makeRequestUrl('user','login'),
            type:'get',
            data:{username: username, password: password},
            beforeSend:function (res) {
                setTimeout(function(){
                    addClass(document.querySelector(".sk-rotating-plane"), "active")
                    document.querySelector(".login").style.display = "none"
                },800)
            },
            success:function (res) {
                if (res.ok){
                    removeClass(document.querySelector(".login"), "active")
                    removeClass(document.querySelector(".sk-rotating-plane"), "active")
                    document.querySelector(".login").style.display = "block"
                }
                layer.msg(res.msg);
                console.log(res)
            }
        })


        /!*setTimeout(function(){
            removeClass(document.querySelector(".login"), "active")
            removeClass(document.querySelector(".sk-rotating-plane"), "active")
            document.querySelector(".login").style.display = "block"
            alert("登录成功")

        },5000)*!/
    }
*/
</script>
</body>
</html>
