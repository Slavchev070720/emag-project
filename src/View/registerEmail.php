<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>registerEmail</title>
    <link rel="stylesheet" href="/css/registerEmail.css">
    <link rel="shortcut icon" sizes="any" type="image/png"
          href="https://s12emagst.akamaized.net/assets/bg/css/icons/favicon.ico?v=1a">
</head>
<body>
<a href="/"><img src="/images/logo-login.png" id="register-img" alt="eMAG"></a>
<div class="reg_mail_container">
    <form action="/user/register-email" method="post" class="form">
        <h1 class="reg-text">Welcome!</h1>
        <h3 class="reg-text2">Please enter e-mail address</h3>
        <input type="email" class="reg_mail" name="email" placeholder="email" max="50" required><br>
        <input type="submit" class="reg_submit-button" name="register-email" value="Continue"> <br>
        <p class="text-login-reg">You already have an account? <a href="/user/view-login-email">Log In</a>
        </p>
        <div id="err" <?= isset($errMsg) ? "" : "style='display: none'"; ?>><?= isset($errMsg) ? $errMsg : ""; ?></div>
    </form>
</div>
</body>
</html>