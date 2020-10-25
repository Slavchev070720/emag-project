<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="shortcut icon" sizes="any" type="image/png"
          href="https://s12emagst.akamaized.net/assets/bg/css/icons/favicon.ico?v=1a">
    <link rel="stylesheet" href="/css/login-user.css">
</head>
<body>
<a href="/"><img src="/images/logo-login.png" id="register-img" alt="eMAG"></a>
<div class="reg_mail_container">
    <form action="/user/login-user" method="post" class="form">
        <h2>You are login as <?= $params['loginEmail'] ?></h2>
        <h3 class="reg-text">Please enter your password</h3>
        <input type="password" class="reg_mail" name="password" placeholder="Password" required><br>
        <input type="submit" class="reg_submit-button" name="login" value="Log In"> <br>
        <p class="text-login-reg"><a href="/user/view-login-email">Go Back</a></p>
        <div id="err" <?= isset($errMsg) ? "" : "style='display: none'"; ?>><?= isset($errMsg) ? $errMsg : ""; ?></div>
    </form>
</div>
</body>
</html>