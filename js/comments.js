var commets = '';

function createComment(nickname, text) {
    if ((nickname !== 'undefined' && nickname.trim() != '') &&
        (text !== 'undefined' && text.trim() != '')) {
        let row = document.createElement('row');
        row.className = 'row pb-3';

        let col = document.createElement('col');
        col.className = 'col d-flex flex-column';

        let comment = document.createElement('div');
        comment.className = 'comment align-self-center';
        comment.style = 'display: inline';

        let nickname_ = document.createElement('h3');
        nickname_.className = 'comment__nickname';
        nickname_.innerText = nickname;

        let content = document.createElement('span');
        content.className = 'comment__content';
        content.innerText = text;

        comment.appendChild(nickname_);
        comment.appendChild(content);
        col.appendChild(comment);
        row.appendChild(col);

        document.getElementById('comments').appendChild(row);
    }
}

function loadComments() {
    fetch('/backend/CommentsController.php/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({
            'function': 'LoadComments'
        })
    }).then((response) => {
        return response.text().then(function(text) {
            comments = JSON.parse(text);

            for (let i = 0; i < comments.length; i++)
                for (let key in comments[i])
                    for (let key_ in comments[i][key])
                        createComment(key_, comments[i][key][key_])
        });
    });
}

function sendComment() {
    let content = document.getElementById('comment-textarea').value;
    let login = '';

    fetch('/backend/CommentsController.php/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({
            'function': 'GetUserLogin'
        })
    }).then((response) => {
        return response.text().then(function(text) {
            if (text == 'null')
                alert('Оставлять комментарии могут только авторизированные пользователи');
            else {
                login = text;
                createComment(login, content);

                fetch('/backend/CommentsController.php/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify({
                        'function': 'SaveComment',
                        'page': 'test',
                        'content': content
                    })
                }).then((response) => {
                    return response.text().then(function(text) {
                    });
                });
            }
        });
    });
}

function checkAuth() {
    fetch('/backend/ProfileController.php/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({
            'function': 'CheckAuth'
        })
    }).then((response) => {
        return response.text().then(function(text) {
            if (text == 'false')
                document.location = 'auth.php';
        });
    });
}