<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
    <link href="../src/css/gq.css" rel="stylesheet">
    <title>版本更新记录</title>
</head>
<body>
<style>
    body {
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
                <table class="layui-table">
                    <colgroup>
                        <col width="150">
                        <col width="150">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>更新内容</th>
                        <th>上线时间</th>
                    </tr>
                    </thead>
                    <tbody id="rules-container">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-row" style="background: white;padding: 20px">
            <p>关于AI记忆功能:</p>
            <p>以前不能和AI进行对话，一直是问一句答一句，AI也不会记忆我们本次页面中的问题和答案</p>
            <p>这个功能出来之后，AI将记住我们本次页面中的所有问题和答案，可以直接和AI进行对话了,但是随之而来的是token会耗费的更多.</p>
            <p>当然如果想和以前一样，不让AI记住，可以问完一个问题之后，刷新页面，就会重置AI记忆</p>
        </div>
    </div>
</div>
<script src="../src/js/gq.js"></script>
<script src="../src/layui/src/layui.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    listRechargeRules();

    function listRechargeRules() {
        $.ajax({
            url: makeRequestUrl('VersionController', 'records'),
            success: function (res) {
                let list = res.obj.list;
                let html = '';
                for (let i = 0; i < list.length; i++) {
                    let htmlItem = '';
                    let dataItem = list[i];
                    htmlItem += '<tr>';
                    htmlItem += '<td>';
                    htmlItem += dataItem.remark;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.created;
                    htmlItem += '</td>';

                    htmlItem += '</tr>';
                    html += htmlItem;
                }

                $('#rules-container').append(html);
            }
        })
    }

    layui.use(function () {
        var layer = layui.layer;
        var form = layui.form;

        form.on('submit(commit)', function (data) {
            console.log(data)
            let content = data.field.content;
            let order_id = data.field.order_id;

            $.ajax({
                type: 'get',
                url: makeRequestUrl('Help', 'create'),
                data: {content: content, userid: 1},
                success: function (res) {
                    successAfter(res)
                    console.log(res)
                    if (res.ok) {
                        layer.msg('感谢反馈，我们将及时跟进您的建议');
                        $('textarea[name=content]').val('');
                        // window.location.reload();
                    } else {
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
