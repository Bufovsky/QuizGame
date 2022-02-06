<!DOCTYPE HTML>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Quiz</title>
    <style>
    .background {
        background-image: url('/public/background.jpg');
        background-size: 100% 100%;
        width: 100vw;
        height: 100vh;
        position: absolute;
        top:0;
        left:0;
        z-index: -1;
    }
    .content {
        font-family: "Century Gothic", CenturyGothic, sans-serif;
        position: absolute;
        bottom: 11%;
        height: 19%;
        width: 50%;
        right: 0;
        left: 25%;
        text-align: center;
        color: #FFF;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow-y: auto;
    }
    a {
        color: #FFF;
    }
    .profile {
        position: absolute;
        right: 20px;
        top: 20px;
        border: 0px;
        width: 32px;
        height: 32px;  
        background-image: url('/public/user.svg');
        background-size: 32px 32px;
    }
</style>
</head>

<body>

<div class=" background"></div>
@auth
<a href="{{ route('user') }}"<div class="profile"></div></a>
@endauth
<div class="content">
    @yield('content')
</div>

</body>

</html>