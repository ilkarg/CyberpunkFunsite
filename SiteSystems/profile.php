<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Профиль</title>
</head>
<body>
<div class="container-fluid">
        <div class="row pt-3">
            <div class="col d-flex flex-column">
                <span class="align-self-center">Логин - <span id='profile-login'></span></span>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col d-flex flex-column">
                <h3 class="align-self-center">Смена никнейма</h3>
                <input type="text" class="align-self-center" id='profile-new-nickname-input' placeholder="Новый никнейм">
                <br>
                <input type="password" class="align-self-center" id='profile-password-input' placeholder="Пароль">
                <br>
                <input type="button" class="btn btn-primary align-self-center" value="Сменить" onclick='changeNickname()'>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col d-flex flex-column">
                <h3 class="align-self-center pb-2">Смена пароля</h3>
                <input type="password" class="align-self-center" id='profile-current-password-input' placeholder="Текущий пароль">
                <br>
                <input type="password" class="align-self-center" id='profile-new-password-input' placeholder="Новый пароль">
                <br>
                <input type="password" class="align-self-center" id='profile-repeat-new-password-input' placeholder="Повторите новый пароль">
                <br>
                <input type="button" class="btn btn-primary align-self-center" value="Сменить" onclick="changePassword()">
            </div>
        </div>
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/profile.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            checkAuth();
            getUserLogin();
        });
    </script>
</body>
</html>