<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
    <link href="../src/css/gq.css" rel="stylesheet">
    <title>个人中心</title>
</head>
<body>
<style>
    body{
        height: 100%;
        background: linear-gradient(to bottom right, #000, #2073c2 60%, #dff6ff);
    }
</style>
<div class="layui-fluid">
    <div class="layui-header layui-bg-black	">
        <ul class="layui-nav" lay-filter="">
            <li class="layui-nav-item"><a href="./list2.php">历史会话</a></li>
            <li class="layui-nav-item"><a href="./chatGpt.php">chatGPT</a></li>
<!--            <li class="layui-nav-item"><a href="./help2.php">联系我们</a></li>-->
<!--            <li class="layui-nav-item layui-this"><a href="./recharge.php">充值/开会员</a></li>-->
            <li class="layui-nav-item">
                <a href="javascript:;">其他</a>
                <dl class="layui-nav-child">
                    <dd><a href="./user.php">个人中心</a></dd>
                    <dd><a href="./recharge.php">充值/开会员</a></dd>
                    <dd><a href="./invite.php">邀请奖励</a></dd>
                    <dd><a href="./help2.php">反馈问题</a></dd>
                </dl>
            </li>
        </ul>
    </div>
    <hr class="layui-border-black">

    <div class="layui-container gq-height-100">
        <div class="layui-row">
            <div class="layui-col-md12">
                <div><h3 style="color: white">token剩余：<span id="balance"></span></h3></div>
            </div>

            <div class="layui-col-md12 log-container" style="color: white;margin-top: 20px">
                <div class="log-title"><h3>token消耗记录</h3></div>
                <table class="layui-table">
                    <colgroup>
                        <col width="150">
                        <col width="200">
                        <col width="200">
<!--                        <col>-->
                    </colgroup>
                    <thead>
                    <tr>
                        <th>tokens</th>
                        <th>备注</th>
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody id="log">

                    </tbody>
                </table>
            </div>
        </div>
<!--        <form class="layui-form" action="">
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
-->
    </div>
</div>
<script src="../src/js/gq.js"></script>
<script src="../src/layui/src/layui.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    listBill();
    getBalance();
    function listBill(){
        $.ajax({
            url:makeRequestUrl('BillController','listBill'),
            type:'get',
            success:function (res) {
                let list = res.obj;

                let html = makeBodyHtml(list);
                $('#log').html(html);
                console.log(res)
            }
        });
    }

    function makeBodyHtml(list){
        let html = '';
        for (let i=0;i<list.length;i++){
            let item = list[i];
            html += '<tr>';
            html += '<td>';
            html += item['tokens'];
            html += '</td>';

            html += '<td>';
            html += item['remark'];
            html += '</td>';

            html += '<td>';
            html += item['created_time'];
            html += '</td>';
            html += '</tr>';
        }
        return html;
    }

    function getBalance(){
        $.ajax({
            url:makeRequestUrl('BillController','balance'),
            type:'get',
            success:function (res) {
                let balance = res.obj.balance;
                $('#balance').text(balance);
                console.log(res)
            }
        });
    }

    layui.use(function(){
        var layer = layui.layer;
        var form = layui.form;

        form.on('submit(commit)', function (data){
            console.log(data)
            let content = data.field.content;
            let order_id = data.field.order_id;

            $.ajax({
                type:'get',
                url:makeRequestUrl('Help','create'),
                data: {content:content,userid:1},
                success:function (res){
                    successAfter(res)
                    console.log(res)
                    if (res.ok){
                        layer.msg('感谢反馈，我们将及时跟进您的建议');
                        $('textarea[name=content]').val('');
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
