function auth() {
    let login = document.getElementById('login-input').value;
    let password = document.getElementById('password-input').value;

    if ((login !== 'undefined' && login.trim() != '') && (password !== 'undefined' && password.trim() != '')) {
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
                else
                    alert('Вы успешно вошли! Логин - ' + text);
            });
        });
    } else
        alert('Все поля должны быть заполнены');
}

function registration() {
    let login = document.getElementById('login-input').value;
    let password = document.getElementById('password-input').value;

    if ((login !== 'undefined' && login.trim() != '') && (password !== 'undefined' && password.trim() != '')) {
        fetch('/backend/AuthController.php/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({
                'function': 'Registration',
                'login': login,
                'password': password
            })
        }).then((response) => {
            return response.text().then(function(text) {
                if (text == 'null')
                    alert('Пользователь с данным логином уже зарегистрирован');
                else
                    alert('Вы успешно зарегистрировались! Логин - ' + text);
            });
        });
    } else
        alert('Все поля должны быть заполнены');
}