

function makeRequestUrl(controller, action){
    return '/questionService/index.php?c=' + controller + '&a=' + action + '&access_token=' + getToken();
}

function makeRequestUrl2(controller, action){
    return '/questionService/index2.php?c=' + controller + '&a=' + action + '&access_token=' + getToken();
}

function getRandom(min, max){
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function saveToken(token) {
    localStorage.setItem('access_token',token);
}

function saveUserid(userid) {
    localStorage.setItem('user_id',userid);
}

function isAdmin(){
    let userid=  getUserid();
    return 1 == userid || 10 == userid
}

function getUserid() {
    return localStorage.getItem('user_id');
}

function getToken(){
    return localStorage.getItem('access_token');
}

function tokenEmpty(){
    //accessToken没有传递
    alert('非法请求，请先登录');
    window.location.href = './wxLogin2.php';
    return ;
}

function tokenExpired(){
    //accessToken已经过期，需要重新登录
    alert('您的登录状态已过期，请重新登录');
    window.location.href = './wxLogin2.php';
}

function successAfter(res) {
    if (res.obj && res.obj.error_code){
        if (res.obj.error_code === 1){
            tokenEmpty();
        }else if (res.obj.error_code === 2){
            tokenExpired();
        }
        return ;
    }
}

function checkingLoginStatus(){
    let token = getToken();
    console.log(token)
    if (!token){
        tokenEmpty();
    }
}

//判断是否是手机 true = pc  false = mobile
function isPc() {
    var userAgentInfo=navigator.userAgent;
    var Agents =new Array("Android","iPhone","SymbianOS","Windows Phone","iPad","iPod");
    var flag=true;
    for(var v=0;v<Agents.length;v++) {
        if(userAgentInfo.indexOf(Agents[v])>0) {
            flag=false;
            break;
        }
    }

    /*if (flag == true){
        alert('pc端');
    }else{
        alert('手机端');
    }*/
    return flag;
}
