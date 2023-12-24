<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<div id="text">

</div>
</body>
<script>
    let divTyping = document.getElementById('text')
    let i = 0,
        timer = 0,
        str = 'hello world\n你好世界'

    function typing () {
        if (i <= str.length) {
            // let char = str.slice(0, i++);
            let data = str.slice(0, i++)
            console.log(data)
            console.log(i)
            divTyping.innerHTML = '<span class="word">'+data+'</span>'+ '<span class="cursor">|</span>';
            timer = setTimeout('typing()', 200)
        }
    }
    function disappear() {
        let cursor = document.querySelector('.cursor');
        cursor.innerHTML = '';
        setTimeout(appear,200);
    }
    function appear() {
        let cursor = document.querySelector('.cursor');
        cursor.innerHTML = '|';
        setTimeout(disappear,200);
    }

    typing();
    disappear();
</script>
</html>
