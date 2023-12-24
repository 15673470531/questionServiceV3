<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../../src/layui/src/css/layui.css" rel="stylesheet">
    <link href="../../src/css/gq.css" rel="stylesheet">
    <title>管理员充值</title>
</head>
<body>
<style>
    body {
        height: 100%;
        background: linear-gradient(to bottom right, #000, #2073c2 60%, #dff6ff);
    }
</style>
<div class="layui-fluid">
    <div class="layui-container">
        <div class="layui-col-md12">
            <form class="layui-form" action="" style="margin-top: 50px">
                <div class="layui-form-item">
                    <label class="layui-form-label" style="color: white">充值账号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="user_id" required lay-verify="required" placeholder="充值的用户ID"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" style="color: white">充值金额</label>
                    <div class="layui-input-inline">
                        <input type="text" name="amount" required lay-verify="required" placeholder="充值金额"
                               autocomplete="off" class="layui-input">
                    </div>
                    <!--                    <div class="layui-form-mid layui-word-aux">辅助文字</div>-->
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">立即充值</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>

            <div style="color: white;text-align: center">用户反馈列表</div>
            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col width="150">
                </colgroup>
                <thead>
                <tr>
                    <th>用户ID</th>
                    <th>反馈内容</th>
                </tr>
                </thead>
                <tbody id="help-container">
                </tbody>
            </table>

            <div style="color: white;text-align: center;margin-top: 20px">充值规则表</div>
            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col width="150">
                </colgroup>
                <thead>
                <tr>
                    <th>充值金额</th>
                    <th>可购买到的token数量/月</th>
                </tr>
                </thead>
                <tbody id="rules-container">
                </tbody>
            </table>

            <div style="color: white;text-align: center;margin-top: 20px">用户列表</div>
            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col width="150">
                </colgroup>
                <thead>
                <tr>
                    <th>用户ID</th>
                    <th>剩余token</th>
                    <th>登录次数</th>
                    <th>推荐人</th>
                    <th>最后活跃时间</th>
                    <th>最后登录时间</th>
                    <th>注册时间</th>
                </tr>
                </thead>
                <tbody id="users-container">
                </tbody>
            </table>

        </div>
    </div>
</div>
<script src="../../src/js/gq.js"></script>
<script src="../../src/layui/src/layui.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    listRechargeRules();
    listHelp();
    listUsers();

    function listUsers(){
        $.ajax({
            url: makeRequestUrl('AdminController', 'listUsers'),
            success: function (res) {
                let users = res.obj;
                let html = '';
                for (let i = 0; i < users.length; i++) {
                    let htmlItem = '';
                    let dataItem = users[i];
                    htmlItem += '<tr>';
                    htmlItem += '<td>';
                    htmlItem += dataItem.id;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.balance_token;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.login_times;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.referee_uid;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.last_active_time;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.last_login_time;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.created_time;
                    htmlItem += '</td>';

                    htmlItem += '</tr>';
                    html += htmlItem;
                }
                $('#users-container').append(html);
            }
        })

    }

    function listRechargeRules() {
        $.ajax({
            url: makeRequestUrl('BalanceController', 'rechargeRules'),
            success: function (res) {
                let rules = res.obj;
                let html = '';
                for (let i = 0; i < rules.length; i++) {
                    let htmlItem = '';
                    let dataItem = rules[i];
                    htmlItem += '<tr>';
                    htmlItem += '<td>';
                    htmlItem += dataItem.money;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.data.text;
                    htmlItem += '</td>';

                    htmlItem += '</tr>';
                    html += htmlItem;
                }
                $('#rules-container').append(html);
            }
        })
    }

    function listHelp() {
        $.ajax({
            url: makeRequestUrl('HelpController', 'listHelp'),
            success: function (res) {
                let helps = res.obj;
                let html = '';
                for (let i = 0; i < helps.length; i++) {
                    let htmlItem = '';
                    let dataItem = helps[i];
                    htmlItem += '<tr>';
                    htmlItem += '<td>';
                    htmlItem += dataItem.user_id;
                    htmlItem += '</td>';

                    htmlItem += '<td>';
                    htmlItem += dataItem.content;
                    htmlItem += '</td>';

                    htmlItem += '</tr>';
                    html += htmlItem;
                }
                $('#help-container').append(html);
            }
        })
    }


    layui.use(function () {
        var layer = layui.layer;
        var form = layui.form;

        form.on('submit(formDemo)', function (data) {
            console.log(data)
            let user_id = data.field.user_id;
            let amount = data.field.amount;

            $.ajax({
                type: 'get',
                url: makeRequestUrl('BalanceController', 'recharge'),
                data: {user_id, amount},
                success: function (res) {
                    console.log(res)
                    if (res.ok) {
                        layer.msg('充值成功');
                    } else {
                        layer.msg('充值失败: ' + res.msg)
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
