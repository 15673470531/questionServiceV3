<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
    <title>大数据问题列表</title>
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
<div class="layui-fluid">
    <div class="layui-header layui-bg-black	">
        <ul class="layui-nav" lay-filter="">
            <li class="layui-nav-item layui-this"><a href="">问答列表</a></li>
            <li class="layui-nav-item"><a href="./home.php">提问大数据</a></li>
            <li class="layui-nav-item"><a href="./help.php">联系我们</a></li>
        </ul>
    </div>
    <hr class="layui-border-black">


    <div class="layui-container">
        <div class="layui-row question-item">
            <div class="layui-col-md6 layui-col-xs12">
                <div class="layui-bg-green question-item-container" style="padding: 10px" onclick="toDetail()">
                    <div class="layui-tab-title"><h2>小屁面包是干啥的</h2></div>
                    <div class="layui-tab-content">
                        小皮面包是一种面制品，主要用于制作三明治或作为早餐的主食。它外皮金黄酥脆，内部松软，口感香甜，适合配以各种配料如蔬菜、肉类、奶酪等，制作成美味的三明治。此外，小皮面包也可以搭配咖啡、茶或牛奶作为早餐或下午茶的搭配食品。
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row question-item">
            <div class="layui-col-md6">
                <div class="layui-bg-green question-item-container" style="padding: 10px">
                    <div class="layui-tab-title"><h2>加油站人员考勤及考核方案</h2></div>
                    <div class="layui-tab-content">
                        考勤方案： 1. 建立考勤制度，规定加油站人员的上班时间和下班时间，以及每天的休息时间和加班时间要求。 2. 使用考勤系统，如指纹打卡、刷卡等方式，记录每位加油站人员的考勤情况。确保考勤数据的准确性和可靠性。 3. 考勤记录应包括加油站人员的上班时间、下班时间、迟到次数、早退次数、旷工次数、请假次数等。 考
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row question-item">
            <div class="layui-col-md6">
                <div class="layui-bg-green question-item-container" style="padding: 10px">
                    <div class="layui-tab-title"><h2>前端开发工程师试用期工作总结</h2></div>
                    <div class="layui-tab-content">
                        在试用期间，作为一名前端开发工程师，我努力积累了一些经验和技能，在工作中取得了一些成绩。 首先，我学习并掌握了前端开发的基本知识和技能。我深入了解了HTML、CSS和JavaScript，并学会了使用各种前端框
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../src/js/gq.js"></script>
<script src="../src/layui/src/layui.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    // this.getList();
    function getList(){
        $.ajax({
            url:makeRequestUrl('Question','list'),
            type:'get',
            data:{userid:0},
            success:function (data) {
                successAfter(data)
                let list = data.obj;

                let str = '';
                for (let i=0;i< list.length;i ++){
                    let item = list[i];
                    str += '<div class="layui-row question-item">';
                    str += '<div class="layui-col-md6 layui-col-xs12 layui-col-sm12">';
                    str += '<div class="question-item-container" style="padding: 10px;background-color: #dddddd" onclick="toDetail('+item.id+')">'
                    str += '<div class="layui-tab-title"><h2>'+item.question+'</h2></div>'
                    str += '<div class="layui-tab-content" style="font-size: 14px">';
                    str += item.reply_content || '待回复';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                }
                $('.layui-container').html(str);
                console.log(data)
            }
        })
    }

    function toDetail(questionId){
        window.location.href = './detail.php?question_id=' + questionId;
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
