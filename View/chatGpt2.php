<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="../src/layui/src/css/layui.css" rel="stylesheet">
    <link href="../src/css/gq.css?a=1" rel="stylesheet">
    <title>chatGPT</title>
</head>

<style>
    body {
        width: 100%;
        /*height: 1000px;*/
        background: linear-gradient(to bottom right, #000, #2073c2 60%, #dff6ff);
        overflow: hidden;
    }

    span {
        display: block;
        position: absolute;
        border-radius: 50%;
    }

    #leave-message-textarea {
        border-radius: 5px;
        height: 34px;
        line-height: 34px;
        padding: 5px;
        width: 100%;
        min-height: 20px;
        max-height: 70px;
        outline: 0;
        border: 1px solid #d2d2d2;
        font-size: 13px;
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-user-modify: read-write-plaintext-only;
        background-color: white;
    }

    [contentEditable=true]:empty:not(:focus):before {
        content: attr(data-text);
    }

    #questionCommit {
        background: #69707a;
        font-weight: 700;
        color: #fff;
    }
</style>
<body>
<div class="layui-fluid gq-height-100" style="width: 100%;display: flex;flex-direction: column;padding: 0">
    <div class="layui-header layui-bg-black	">
        <ul class="layui-nav" lay-filter="">
            <li class="layui-nav-item"><a href="./list2.php">历史会话</a></li>
            <li class="layui-nav-item layui-this"><a href="">chatGPT</a></li>
            <!--            <li class="layui-nav-item"><a href="./help2.php">联系我们</a></li>-->
            <!--            <li class="layui-nav-item"><a href="./recharge.php">充值/开会员</a></li>-->
            <li class="layui-nav-item">
                <a href="javascript:;">其他</a>
                <dl class="layui-nav-child">
                    <dd><a href="./user.php">个人中心</a></dd>
                    <dd><a href="./recharge.php">充值/开会员</a></dd>
                    <dd><a href="./invite.php">邀请奖励</a></dd>
                    <dd><a href="./help2.php">反馈问题</a></dd>
                    <dd><a href="./version.php">版本记录</a></dd>
                </dl>
            </li>
        </ul>
    </div>
    <hr class="layui-border-black">

    <div class="layui-container gq-height-73 layui-col-md12 layui-col-xs12 layui-col-sm12 layui-col-lg12"
         style="width: 100%">

        <!--聊天框-->
        <div class="layui-row gq-row-center gq-height-100">
            <div class="layui-col-md7  layui-col-xs12 layui-col-sm12" id="msg-container"
                 style="background-color: #ededed;padding: 10px;overflow: auto">
                <!--  <div class="gq-msg-time">23:43</div>
                  <div class="gq-msg gq-right-msg">
                      <img class="gq-msg-avatar" src="../src/imgs/head.png">
                      <div class="gq-msg-text">这个世界上最高的山峰是什么</div>
                  </div>
                  <div class="gq-msg gq-left-msg">
                      <img class="gq-msg-avatar" src="../src/imgs/chatgpt.svg">
                      <div class="gq-msg-text">你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞</div>
                  </div>-->

                <div id="markdown">

                </div>
            </div>
        </div>

        <div style="padding: 10px 0px 10px 0px"></div>

        <div class="layui-row gq-row-center">
            <div class="layui-form layui-col-md7 layui-col-xs12 layui-col-sm12">
                <div class="layui-row layui-col-md12 layui-col-xs12 layui-col-sm12">
                    <div class="layui-input-group layui-col-md12 layui-col-xs12 layui-col-sm12">
                        <div id="leave-message-textarea" contenteditable="true" data-text="请输入消息内容"
                             style="font-size: 16px"></div>
                        <div class="layui-input-suffix"
                             style="width: 0;height: 34px;line-height: 34px;padding: 0px 0px 0px 10px;">
                            <button id="questionCommit" type="submit" class="layui-btn layui-btn-primary gq-height-100"
                                    style="border-radius: 5px;font-size: 16px" onclick="commit()">发送
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/marked@3.0.0/marked.min.js"></script>-->

<script src="../src/js/gq.js"></script>
<!--服务器太垃圾，加载有点慢-->
<!--<script src="../src/js/start.js"></script>-->
<script src="../src/layui/src/layui.js"></script>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    const newest_question_id = 0;

    checkingLoginStatus();

    /*document.getElementById('markdown').innerHTML =
        marked.parse('# Marked in the browser\n\nRendered by **marked**.');*/
    /*var m=document.querySelectorAll('code[markdown]');
    for(var i=0;i<m.length;i++){
        m[i].outerHTML='<div>'+marked(m[i].innerHTML)+'</div>';
    }*/

    //防止input被输入框遮挡
    $('#leave-message-textarea').on('focus', function () {
        let target = this;
        setTimeout(function () {
            target.scrollIntoView(false)
        }, 200)
    });

    listLatelyQuestions();

    function showDefaultAiMsg() {
        let msg = '您好, 我是一个AI机器人，被称为OpenAI助手。我可以帮助回答各种问题和提供信息。有什么我可以帮到你的吗？';
        let leftHtml = getLeftMsgHtml(msg);
        appendToContainer(leftHtml)
    }

    function listLatelyQuestions() {
        let myDate = new Date();
        let year = myDate.getFullYear();
        let month = myDate.getMonth() + 1;
        let date = myDate.getDate();
        let time = year + '-' + month + '-' + date;

        let that = this;

        // $('#msg-container').append(getActivityHtml('活动: 通过邀请链接邀请好友注册可获得3万的token奖励哦'));
        // $('#msg-container').append(getVersionHtml('最新通知：新增【版本记录】模块，新功能【AI记忆功能】即将上线,点我查看详情'));
        $('#msg-container').append(getVersionHtml('最新通知：新功能【AI记忆功能】已上线'));
        // $('#msg-container').append(getNewVersionHtml('点我体验测试版流式输出chatGPT，更加丝滑'));

        let url = makeRequestUrl('Question', 'listLatelyQuestions');
        console.log(url)
        $.ajax({
            url: url,
            type: 'get',
            data: {num: 2},
            success: function (res) {
                successAfter(res)
                let list = res.obj.list;
                that.newest_question_id = res.obj.newest_question_id;

                for (let i = 0; i < list.length; i++) {
                    let data = list[i];
                    let rightMsg = data.question;
                    let leftMsg = data.reply_content;
                    let rightHtml = getRightMsgHtml(rightMsg);
                    // let markHtml = marked.parse('# Marked in the browser\n\nRendered by **marked**.')
                    let leftHtml = getLeftMsgHtml(leftMsg);
                    appendToContainer(rightHtml)
                    appendToContainer(leftHtml)
                }
                showDefaultAiMsg();
                console.log(res)
            }
        })
    }

    function lockRequest() {
        $('#questionCommit').attr('disabled', true);
        $('#questionCommit').addClass('layui-disabled');
    }

    function lockReleaseRequest() {
        $('#questionCommit').attr('disabled', false);
        $('#questionCommit').removeClass('layui-disabled');
        console.log('删除禁用')
    }

    function getRightMsgHtml(content) {
        let str = '';
        str += '<div class="gq-msg gq-right-msg">';
        str += '<img class="gq-msg-avatar" src="../src/imgs/user.jpeg">';
        str += '<div class="gq-msg-text">' + content + '</div>';
        str += '</div>';
        return str;
    }

    function getLeftMsgHtml(content) {
        //转换markdown
        let markHtml = '';
        if (isPc()) {
            markHtml = marked.parse(content)
        } else {
            markHtml = content
        }

        // 你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞
        let str = '';
        str += '<div class="gq-msg gq-left-msg">'
        str += '<img class="gq-msg-avatar" src="../src/imgs/chatgpt.svg">'
        str += '<div class="gq-msg-text">' + markHtml + '</div>';
        str += '</div>'
        return str;
    }

    function getLeftMsgHtmlReply(content, randomId, isMarkdown = true) {
        //转换markdown
        let markHtml = '';
        if (isMarkdown && isPc()) {
            markHtml = marked.parse(content)
        } else {
            markHtml = content
        }

        let id = 'msg-text-' + randomId;

        // 你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞你好这个世界最高的山峰是珠穆朗玛峰哦，嘻嘻羞
        let str = '';
        str += '<div class="gq-msg gq-left-msg">'
        str += '<img class="gq-msg-avatar" src="../src/imgs/chatgpt.svg">'
        str += '<div class="gq-msg-text" id="' + id + '">' + markHtml + '</div>';
        str += '</div>'
        return str;
    }


    function getLeftLoadingMsgHtml(content) {
        let str = '';
        str += '<div class="gq-msg gq-left-msg" id="loading-msg">'
        str += '<img class="gq-msg-avatar" src="../src/imgs/chatgpt.svg">'
        str += '<div class="gq-msg-text">' + content + '</div>';
        str += '</div>'
        return str;
    }

    function removeLeftLoadingMsgHtml() {
        // $('#msg-container').remove('#loading-msg');
        $('#loading-msg').remove();
    }

    function getActivityHtml(msg) {
        let str = '';
        str += '<div style="color: red" class="gq-msg-time"><a href="./invite.php">' + msg + '</a></div>';
        return str;
    }

    function getVersionHtml(msg){
        let str = '';
        str += '<div style="color: red" class="gq-msg-time"><a href="./version.php">' + msg + '</a></div>';
        return str;
    }

    function getNewVersionHtml(msg) {
        let str = '';
        str += '<div style="color: red" class="gq-msg-time"><a href="./chatGpt2.php">' + msg + '</a></div>';
        return str;
    }

    function getTimeHtml(time) {
        let str = '';
        str += '<div class="gq-msg-time">' + time + '</div>';
        return str;
    }

    function appendToContainer(html) {
        $('#msg-container').append(html);
    }

    function scrollToBottom() {
        // $('#leave-message-textarea').scrollTop(1000);

        let textarea = document.getElementById('msg-container');
        // console.log(textarea.scrollHeight)
        textarea.scrollTop = textarea.scrollHeight;
        // textarea.scrollTop = 0;
    }

    function commit() {
        //获取问题
        let content = $('#leave-message-textarea').text().trim();

        if (!content) {
            layer.msg('内容为空');
            return;
        }

        //插入时间html
        let myDate = new Date();
        let time = myDate.getHours() + ':' + myDate.getMinutes();
        $('#msg-container').append(getTimeHtml(time));

        //插入问题html
        let rightMsgHtml = getRightMsgHtml(content);
        $('#msg-container').append(rightMsgHtml);

        scrollToBottom();

        //清空输入框
        $('#leave-message-textarea').text('');

        let randomId = getRandom(1000, 10000);

        //滚动条置底
        // scrollToBottom();
        $.ajax({
            url: makeRequestUrl2('UserController', 'isCanCommitQuestion'),
            type: 'post',
            data: {question: content, random_id: randomId, newest_question_id: this.newest_question_id},
            beforeSend: function (res) {
                console.log('请求中')
                //锁住发送按钮
                lockRequest();

                //请求中
                let leftHtml = getLeftLoadingMsgHtml('正在努力为您查找大数据结果请稍后...');
                appendToContainer(leftHtml);
                scrollToBottom();
            },
            success: function (res) {
                if (!res.ok) {
                    layer.msg(res.msg);
                    return;
                }

                let allHtml = '';

                let url = makeRequestUrl('AiController', 'eventStream') + '&random_id=' + randomId;
                let eventSource = new EventSource(url);

                let id = 'msg-text-' + randomId;

                eventSource.onopen = function (event) {
                    removeLeftLoadingMsgHtml()
                }

                eventSource.addEventListener('message', function (event) {
                    let data = event.data
                    // console.log('接受服务器数据' + data)

                    allHtml += data;
                    let containerId = document.getElementById(id)
                    if (containerId) {
                        containerId.innerHTML += data;
                    } else {
                        let letMsgHtml = getLeftMsgHtmlReply(data, randomId, false);
                        console.log(letMsgHtml)
                        $('#msg-container').append(letMsgHtml);
                    }
                    scrollToBottom();
                })

                eventSource.addEventListener('close', function (event) {
                    let data = event.data
                    // console.log('接受服务器关闭命令' + event)
                    // let containerId = document.getElementById(id)

                    // console.log('转换之前的: ' + allHtml)

                    // let markHtml = marked.parse('# Marked in the browser\n\nRendered by **marked**.')

                    // let markHtml = marked.parse(allHtml)
                    // console.log('转成mark: ' + markHtml)
                    // containerId.innerHTML = markHtml;
                    eventSource.close();
                })


                eventSource.addEventListener('error', function (event) {
                    let data = event.data
                    // console.log('接受服务器报错命令' + event)
                    eventSource.close();
                })
                /*eventSource.onmessage = function (event){
                    let data = event.data
                    console.log('接受服务器数据' + data)
                    console.log('接受服务器数据' + event)
                }*/
            },
            complete: function (res) {
                lockReleaseRequest();
                scrollToBottom();
                console.log('请求完成')
            },
            error: function (error) {
                lockReleaseRequest();
                layer.msg('请求出错啦，请联系管理员');
                console.log('请求出错啦')
                console.log(error)
            }
        })

        return;
        //请求后端
        $.ajax({
            // url:'/questionService/index.php?c=Question&a=commit',
            url: makeRequestUrl('Question', 'commit2'),
            type: 'get',
            data: {content: content, order_id: 0},
            beforeSend: function (res) {
                console.log('请求中')
                //锁住发送按钮
                lockRequest();

                //请求中
                let leftHtml = getLeftLoadingMsgHtml('正在努力为您查找大数据结果请稍后...');
                appendToContainer(leftHtml);
                scrollToBottom();
            },
            success: function (res) {
                successAfter(res)
                console.log('请求成功' + res)
                let text = '';
                if (res.ok) {
                    text = res.obj.answer;
                    // let time = res.obj.time;
                } else {
                    text = res.msg;
                    layer.msg(res.msg);
                }
                console.log(text)
                let letMsgHtml = getLeftMsgHtml(text);
                // let timeHtml = getTimeHtml(time);
                $('#msg-container').append(letMsgHtml);
                console.log(res)
            },
            complete: function (res) {
                lockReleaseRequest();
                scrollToBottom();
                removeLeftLoadingMsgHtml()
                console.log('请求完成')
            },
            error: function (error) {
                lockReleaseRequest();
                layer.msg('请求出错啦，请联系管理员');
                console.log('请求出错啦')
                console.log(error)
            }
        })

        // layer.msg(rightMsg);
    }

    /* $(function (){
         $('#leave-message-textarea').bind('keyup',function (event){
             if (event.keyCode === 13){
                 commit();
                 return ;
             }
         });
     });*/

    layui.use(function () {
        var layer = layui.layer;
        var form = layui.form;


        form.on('submit(commit)', function (data) {
            alert(233);
            exit;
            console.log(data)
            let content = data.field.content;
            let order_id = data.field.order_id;

            $.ajax({
                type: 'get',
                url: '../Controller/QuestionRouter.php?action=commit',
                data: {content: content, order_id: order_id},
                success: function (res) {
                    if (res.ok) {
                        layer.msg('提交成功')
                        // window.location.reload();
                    } else {
                        layer.msg('提交失败: '.res.msg)
                    }
                    console.log(res)
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
