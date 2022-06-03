const confirmed = true;
const confirmationCode = '';

function auth() {
    let login = document.getElementById('email').value;
    let password = document.getElementById('pasword').value;

    if ((login != 'undefined' && login.trim() != '') && (password != 'undefined' && password.trim() != '')) {
        fetch('/backend/AuthController.php/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                'function': 'Auth',
                'login': login,
                'password': password
            })
        }).then((response) => {
            return response.text().then(function(text) {
                if (text == 'null')
                    alert('Неверные логин или пароль');
                else {
                    alert('Вы успешно вошли! Логин - ' + text);
                    document.location = 'index.html';
                }
            });
        });
    } else
        alert('Все поля должны быть заполнены');
}

function registration() {
    let mail = document.getElementById('email').value;
    let login = document.getElementById('fio').value;
    let password = document.getElementById('pasword').value;

    if ((login != 'undefined' && login.trim() != '') && (password != 'undefined' && password.trim() != '') &&
        (mail != 'undefined' && mail.trim() != '')) {
        if (confirmed) {
            fetch('/backend/AuthController.php/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({
                    'function': 'Registration',
                    'mail': mail,
                    'login': login,
                    'password': password
                })
            }).then((response) => {
                return response.text().then(function(text) {
                    if (text == 'null')
                        alert('Пользователь с данным логином уже зарегистрирован');
                    else {
                        alert('Вы успешно зарегистрировались! Логин - ' + text);
                        document.location = 'index.html';
                    }
                });
            });
        } else
            alert('Для того, чтобы зарегистрировать аккаунт необходимо подтвердить почту');
    } else
        alert('Все поля должны быть заполнены');
}

function confirmationEmail() {
    let mail = document.getElementById('mail-input').value;
    confirmationCode = getConfirmationCode();

    if (mail !== 'undefined' && mail != '') {
        fetch('/backend/AuthController.php/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                'function': 'ConfirmMail',
                'mail': mail,
                'confirmationCode': confirmationCode
            })
        }).then((response) => {
            return response.text().then(function(text) {
                alert('Письмо с кодом отправлено вам на почту');
            });
        });
    } else
        alert('Поле почта должно быть заполнено');
}

function checkConfirmationCode() {
    let code = document.getElementById('confirmation-code-input').value;

    if (!confirmed) {
        if (parseInt(code) === confirmationCode && /^\S+$/.test(code) === true) {
            confirmed = true;
            alert('Вы успешно подтвердили почту!');
        } else
            alert('Неверный код подтверждения');
    } else
        alert('Почта уже подтверждена');
}

function recoveryPassword() {
    let newPassword = document.getElementById('new-password-input1').value;
    let newPassword2 = document.getElementById('new-password-input2').value;
    let mail = document.getElementById('mail-input').value;

    if (confirmed && (newPassword == newPassword2) && (newPassword !== 'undefined' && newPassword.trim() != '') &&
        (newPassword2 !== 'undefined' && newPassword2.trim() != '') && (mail !== 'undefined' && mail.trim() != '')) {
        fetch('/backend/AuthController.php/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                'function': 'ChangePassword',
                'mail': mail,
                'newPassword': newPassword
            })
        }).then((response) => {
            return response.text().then(function(text) {
                if (text == 'true') {
                    alert('Пароль был успешно изменен!');
                    document.location = 'auth.php';
                }
                else
                    alert('Не удалось восстановить пароль. Возможно допущена ошибка в адресе электронной почты');
            });
        });
    } else
        alert('Для восстановления пароля необходимо подтвердить почту');
}

let getConfirmationCode = () => Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;