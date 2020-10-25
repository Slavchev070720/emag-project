<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Profile</title>
    <link rel="stylesheet" href="/css/my-profile.css">
</head>
<body>
<div id="my-profile-account">
    <img src="/images/logo-login.png" id="account-logo-img" alt="eMAG">
    <form action="/user/edit-profile" method="post" class="form">
        <h3 id="my-prof-text">My Profile</h3>
        <table id="table-my-profile">
            <tr>
                <td><label for="">Password:</label></td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td><label for="">Confirm Password:</label></td>
                <td><input type="password" name="confirm-password"></td>
            </tr>
            <tr>
                <td><label for="">Email:</label></td>
                <td><input type="email" name="email" required minlength="5" maxlength="50"
                           value="<?= $params['userEmail'] ?>"></td>
            </tr>
            <tr>
                <td><label for="">First Name:</label></td>
                <td><input type="text" name="first-name" minlength="2" maxlength="15" required
                           value="<?= $params['userFirstName'] ?>"></td>
            </tr>
            <tr>
                <td><label for="">Last Name:</label></td>
                <td><input type="text" name="last-name" minlength="2" maxlength="15" required
                           value="<?= $params['userLastName'] ?>"></td>
            </tr>
            <tr>
                <td><label for="">Address:</label></td>
                <td><input type="text" name="address"
                           value="<?= $params['userAddress'] ?>"></td>
            </tr>
        </table>
        <input type="submit" id="submit-my-profile" name="edit-profile" value="Save Edit">

        <div id="err" <?= isset($params['errMsg']) ? "" : "style='display: none'"; ?>>
            <?= isset($params['errMsg']) ? $params['errMsg'] : ""; ?></div>
        <a href='/user/delete' onclick="return confirm('Do you really want to delete your profile?')">
            <h2>Delete Account</h2></a>
    </form>
</div>
</body>
</html>