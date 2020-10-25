<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register User</title>
    <link rel="stylesheet" href="/css/registerUser.css">
    <link rel="shortcut icon" sizes="any" type="image/png"
          href="https://s12emagst.akamaized.net/assets/bg/css/icons/favicon.ico?v=1a">
</head>
<body>
<a href="/"><img src="/images/logo-login.png" id="register-img" alt="eMAG"></a>
<div id="register_user">
    <form action="/user/register-user" method="post" class="form">
        <h1 id="reg_text_user">Registration</h1>
        <label for="" class="reg_user">First Name</label><br>
        <input type="text" class="reg_input" name="first-name" placeholder="First name" min="2" max="15"
               value="<?= $params['registerFirstName'] ?>" required><br>
        <label for="" class="reg_user">Last Name </label><br>
        <input type="text" class="reg_input" name="last-name" placeholder="Last name" min="2" max="15"
               value="<?= $params['registerLastName'] ?>" required><br>
        <label for="" class="reg_user">Password </label><br>
        <input type="password" class="reg_input" name="password" placeholder="Password" min="8" max="30" required><br>
        <label for="" class="reg_user">Confirm Password </label><br>
        <input type="password" class="reg_input" name="confirm-password" placeholder="Confirm password" min="6" max="30"
               required><br>
        <input type="submit" class="reg_submit-button" name="register-mail" value="Register"> <br>
        <a href="/user/view-register-email">Go Back</a>
        <div id="err" <?= isset($errMsg) ? "" : "style='display: none'"; ?>><?= isset($errMsg) ? $errMsg : ""; ?></div>
    </form>
</div>
</body>
</html>