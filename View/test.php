<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>测试页面</title>
</head>
<body>
<button type="button" onclick="commitAi()">输出文章</button>
<div id="message"></div>
<script src="../src/js/gq.js"></script>
<script>
    // let url = makeRequestUrl('TestController','outPut')

    function commitAi(){
        // let url = 'http://82.156.139.209/questionService/Controller/other/test.php';
        // let url = 'http://82.156.139.209/questionService/Controller/ChatGpt/AiController.php';

        let url = makeRequestUrl('AiController','eventStream') +'&question=测绘测试';


        console.log(url);
        let source  = new EventSource(url);

       /* source.onmessage = function (event){
            console.log(event.data)
        }

        source.onerror = function () {
            console.log('EventStream error')
        }*/

        console.log('开始')
        source.addEventListener('message', function (event){
            let type = event.type
            let data = event.data
            /*console.log(type)
            console.log('data: ' + data)
            console.log(event)*/
            /*setTimeout(function (){
                console.log('开始关闭')
                source.close();
                console.log('关闭成功')
            },10000)*/
            if (data == '[done]'){
                console.log('接收到关闭')
                source.close();
            }else if (data){

                console.log('接收数据' + data)
                if (html.length >= 10 && lock == 0){
                    html += data;
                    console.log('html超过 10 ' + html)
                    tempData = html;
                    html = '';
                    appendText();

                }else{
                    html += data;
                    tempHtml = html;
                    console.log('开始累加,结果: ' + html);
                }

                // console.log('长度: ' +  data.length)
                // setTimeout(function (){
                //     document.getElementById('message').innerHTML += data;
                // }, 10)
            }
        })

        source.addEventListener('close', function (event){
            console.log('获取到关闭啦')
            source.close();
        })
    }

    let html = '';
    let tempHtml = '';
    let i =1;
    let tempData = '';
    let containerDiv = document.getElementById('message')
    let lock = 0;
    function appendText(){
        lock = 1;
        console.log('首次进入' + tempData)
        let length = tempData.length;
        if (i <= length){
            let temp = tempData.slice(0, i++)
            console.log('朱哥输出: ' + temp)
            let newHtml = '<span class="word">'+temp+'</span>'+ '<span class="cursor">|</span>';
            containerDiv.innerHTML = newHtml;
            console.log('开始插入: ' + newHtml)
            setTimeout('appendText()', 100)
        }else{
            console.log('关闭锁')
            lock = 0
            if (tempHtml){
                tempData += tempHtml;
                tempHtml = '';
                appendText();
            }
        }
    }

   /* document.addEventListener('DOMContentLoaded', function() {
        // 这里放置addEventListener方法的调用代码

        let url = 'http://82.156.139.209/questionService/Controller/other/test.php';

        let source  = new EventSource(url);

        let html = '';
        console.log('开始')
        source.addEventListener('font', function (event){
            console.log(233)
            console.log(event.data)
            html += event.data
            document.getElementById('message').innerHTML = html;
        })
    });*/


    function output(){
        // let url = makeRequestUrl('TestController','outPut')
        let url = 'http://happyjiuhao.top/questionService/Controller/other/test.php';
        let source = new EventSource(url)
        let html = '';

        console.log('点击开始')
        source.onmessage = function (e){
            console.log(233)
            console.log(e)
            console.log(e.data)
            html += e.data
            document.getElementById('message').innerHTML = html;
        }
    }
</script>
</body>
</html>
