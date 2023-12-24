<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div id="message"></div>

<script src="../../src/js/gq.js"></script>
<script>
    let url = makeRequestUrl('CompanyTestController', 'eventStream');
    const source = new EventSource(url);
    source.onopen = function (){
        console.log('open')
    }

    source.onmessage = function (event) {
        let data = event.data;
        if (data == '[done]'){
            source.close();
        }
        console.log('接受服务器消息')
        console.log(event);
    }

    source.onerror = function (event){
        console.log('服务器发生错误' + event)
    }

</script>
</body>
</html>
