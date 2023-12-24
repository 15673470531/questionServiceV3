<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
    <title>联系我们</title>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-header layui-bg-black	">
        <ul class="layui-nav" lay-filter="">
            <li class="layui-nav-item"><a href="./list.php">问答列表</a></li>
            <li class="layui-nav-item"><a href="./home.php">提问大数据</a></li>
            <li class="layui-nav-item layui-this"><a href="">联系我们</a></li>
        </ul>
    </div>
    <hr class="layui-border-black">

    <div class="layui-container">
        <form class="layui-form" action="">
            <div class="layui-form-item layui-form-text">
                <div class="layui-input-block" style="margin-left: 0px">
                    <textarea name="content" placeholder="请输入您想对我们说的话，比如改进或者一些问题" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block" style="margin-left: 0px">
                    <button class="layui-btn" lay-submit lay-filter="commit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>

    </div>
</div>
<script src="../src/layui/src/layui.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    layui.use(function(){
        var layer = layui.layer;
        var form = layui.form;

        form.on('submit(commit)', function (data){
            console.log(data)
            let content = data.field.content;
            let order_id = data.field.order_id;

            $.ajax({
                type:'get',
                url:'../Controller/HelpRouter.php?action=commit',
                data: {content:content,userid:1},
                success:function (res){
                    console.log(res)
                    if (res.ok){
                        layer.msg('感谢反馈，我们将及时跟进您的建议')
                        // window.location.reload();
                    }else{
                        layer.msg('提交失败: ' + res.msg)
                    }
                }
            });
            return false;
        });
        // Welcome
        // layer.msg('Hello World 233', {icon: 1});
    });
</script>
</body>
</html>
