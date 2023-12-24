<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../src/css/gq.css" rel="stylesheet">
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
    <title>会话详情</title>
<!--    我是一个AI机器人，被称为OpenAI助手。我可以帮助回答各种问题和提供信息。有什么我可以帮到你的吗？-->
</head>
<style>
    .question-item{
        display: flex;justify-content: center;
        margin-top: 15px;
    }
    .question-item-container{
        border-radius: 5px;
        cursor: pointer;
        color: black;
    }
    .question-item-container .layui-tab-title{
        color: black;
    }
    .question-item-container .layui-tab-content{
        color: black;
    }
</style>
<body>
<div id="bg"></div>
<div class="layui-fluid">
    <div class="layui-header layui-bg-black	">
        <ul class="layui-nav" lay-filter="">
            <li class="layui-nav-item layui-this"><a href="./list2.php">历史会话</a></li>
            <li class="layui-nav-item"><a href="./chatGpt.php">chatGPT</a></li>
<!--            <li class="layui-nav-item"><a href="help2.php">联系我们</a></li>-->
<!--            <li class="layui-nav-item"><a href="./recharge.php">充值/开会员</a></li>-->
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
        <!--<div class="layui-row question-item">
            <div class="layui-col-md6">
                <div class="layui-bg-green question-item-container" style="padding: 10px">
                    <div class="layui-tab-title"><h2>小屁面包是干啥的</h2></div>
                    <div class="layui-tab-content">
                        小皮面包是一种面制品，主要用于制作三明治或作为早餐的主食。它外皮金黄酥脆，内部松软，口感香甜，适合配以各种配料如蔬菜、肉类、奶酪等，制作成美味的三明治。此外，小皮面包也可以搭配咖啡、茶或牛奶作为早餐或下午茶的搭配食品。
                        在试用期间，作为一名前端开发工程师，我努力积累了一些经验和技能，在工作中取得了一些成绩。 首先，我学习并掌握了前端开发的基本知识和技能。我深入了解了HTML、CSS和JavaScript，并学会了使用各种前端框
                        考勤方案： 1. 建立考勤制度，规定加油站人员的上班时间和下班时间，以及每天的休息时间和加班时间要求。 2. 使用考勤系统，如指纹打卡、刷卡等方式，记录每位加油站人员的考勤情况。确保考勤数据的准确性和可靠性。 3. 考勤记录应包括加油站人员的上班时间、下班时间、迟到次数、早退次数、旷工次数、请假次数等。 考
                    </div>
                </div>
            </div>
        </div>-->
    <div style="text-align: center">tips: <span id="tips"></span></div>
    <div class="layui-container">

    </div>
</div>
<script src="../src/js/gq.js"></script>
<script src="../src/layui/src/layui.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    if (isPc()){

    }else{
        $('#tips').text('用电脑打开才可以看到markdown格式');
    }
    getDetail();
    function getDetail(){
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let id = urlParams.get('question_id');

        $.ajax({
            // url:'../Controller/QuestionRouter.php?action=detail',
            url:makeRequestUrl('Question','questionDetail'),
            type:'get',
            data:{question_id:id},
            success:function (res) {
                successAfter(res)
                let data = res.obj;
                let reply = data.reply_content;

                if (isPc()){
                    reply = marked.parse(reply)
                }

                let str = '';
                    str += '<div class="layui-row question-item">';
                    str += '<div class="layui-col-md6 layui-col-xs12 layui-col-sm12">';
                    str += '<div class="question-item-container" style="padding: 10px;background-color: #dddddd" onclick="toDetail('+data.id+')">'
                    str += '<div class="layui-tab-title"><h2>'+data.question+'</h2></div>'
                    str += '<div class="layui-tab-content" style="line-height: 1.8;font-size: 16px">';
                    str += reply || '待回复';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                $('.layui-container').html(str);
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
            $.ajax('../Controller/QuestionCommit.php', function (data){
                console.log(data)
            });
            return false;
        });
        // Welcome
        // layer.msg('Hello World 233', {icon: 1});
    });
</script>
</body>
</html>
