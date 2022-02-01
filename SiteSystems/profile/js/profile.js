function getUserLogin() {
    fetch('/backend/ProfileController.php/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({
            'function': 'GetUserLogin'
        })
    }).then((response) => {
        return response.text().then(function(text) {
            if (text != 'null')
                document.getElementById('profile-login').innerText = text;
            else
                alert('Какая-то ошибка... text = ' + text);
        });
    });
}

function changePassword() {
    let currentPassword = document.getElementById('profile-current-password-input').value;
    let newPassword = document.getElementById('profile-new-password-input').value;
    let newRepeatPassword = document.getElementById('profile-repeat-new-password-input').value;

    if ((currentPassword !== 'undefined' && currentPassword.trim() != '') &&
        (newPassword !== 'undefined' && newPassword.trim() != '') &&
        (newRepeatPassword !== 'undefined' && newRepeatPassword.trim() != ''))
        if (newPassword == newRepeatPassword) {
            fetch('/backend/ProfileController.php/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({
                    'function': 'CheckUserPassword',
                    'password': currentPassword
                })
            }).then((response) => {
                return response.text().then(function(text) {
                    if (text == 'true') {
                        fetch('/backend/ProfileController.php/', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json;charset=utf-8'
                            },
                            body: JSON.stringify({
                                'function': 'ChangePassword',
                                'password': currentPassword,
                                'newPassword': newPassword
                            })
                        }).then((response) => {
                            return response.text().then(function(text) {
                                if (text == 'true')
                                    alert('Пароль был успешно изменен!');
                                else
                                    alert(text);
                            });
                        });
                    } else
                        alert('Ошибка! Неверно указан текущий пароль');
                });
            });
        } else
            alert('Ошибка! Новый пароль и повтор нового пароля отличаются');

    else
        alert('Ошибка! Все поля должны быть заполнены');
}

function changeNickname() {
    let newNickname = document.getElementById('profile-new-nickname-input').value;
    let password = document.getElementById('profile-password-input').value;

    if ((newNickname !== 'undefined' && newNickname.trim() != '') && (password !== 'undefined' && password.trim() != '')) {
        fetch('/backend/ProfileController.php/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                'function': 'CheckNickname',
                'login': newNickname
            })
        }).then((response) => {
            return response.text().then(function(text) {
                if (text == 'true') {
                    fetch('/backend/ProfileController.php/', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json;charset=utf-8'
                        },
                        body: JSON.stringify({
                            'function': 'ChangeNickname',
                            'login': newNickname,
                            'password': password
                        })
                    }).then((response) => {
                        return response.text().then(function(text) {
                            if (text == 'true') {
                                alert('Никнейм успешно изменен!');
                                document.location.reload();
                            } else
                                alert('Какая-то ошибка... text = ' + text);
                        });
                    });
                } else
                    alert('Error: ' + text);
            });
        });
    } else
        alert('Ошибка! Все поля должны быть заполнены');
}