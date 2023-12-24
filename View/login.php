<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../src/css/gq.css" rel="stylesheet">
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
    <title>登录</title>
</head>
<style>
    body,html{
        width: 100%;
        height: 100%;
    }
</style>
<body>
<div class="layui-container gq-height-100" style="display: flex;flex-direction: column;justify-content: center">
<div class="layui-row" style="display: flex;justify-content: center">
    <div class="" style="border: 1px solid">
        <form class="layui-form" action="" style="margin: 0;padding: 0">
            <div class="layui-form-item" style="display: flex">
                <div class="layui-input-inline">
                    <input type="text" name="title" required  lay-verify="required" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="password" name="password" required lay-verify="required" placeholder="请输入验证码或者密码" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">发送验证码</div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">登录</button>
                    <button type="reset" class="layui-btn layui-btn-primary">注册</button>
                </div>
            </div>
        </form>

        <script>
            //Demo
            layui.use('form', function(){
                var form = layui.form;

                //监听提交
                form.on('submit(formDemo)', function(data){
                    layer.msg(JSON.stringify(data.field));
                    return false;
                });
            });
        </script>
    </div>
</div>
</div>
<script src="../src/layui/src/layui.js"></script>
</body>
</html>
