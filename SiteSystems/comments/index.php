<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Document</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row pt-2">
            <div class="col d-flex flex-column">
                <div class="align-self-center">
                    <h2>Комментарии</h2>
                </div>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col d-flex flex-column">
                <div class="align-self-center">
                    <textarea class="form-control" id="comment-textarea" rows="8" cols="50" style="resize: none"></textarea>
                </div>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col d-flex flex-column">
                <div class="align-self-center">
                    <input type="button" class="btn btn-primary" value="Отправить" onclick="sendComment()">
                </div>
            </div>
        </div>
        <div class="container pt-4" id="comments"></div>
    </div>

    <script src="js/comments.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", (event) => loadComments());
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>