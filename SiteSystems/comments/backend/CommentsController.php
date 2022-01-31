<?php

require_once 'Converter.php';

$data = objectToarray(json_decode(file_get_contents('php://input')));

if (isset($data['function'])) {
    if ($data['function'] == 'LoadComments')
        LoadComments();
    else if ($data['function'] == 'GetUserLogin')
        GetUserLogin();
    else if ($data['function'] == 'auth')
        auth();
    else if ($data['function'] == 'SaveComment')
        SaveComment();
}

function LoadComments() {
    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    $comment = array();
    $comments = array();

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status) {
        $query = "SELECT * FROM comments WHERE page='test'";
        $result = $sql->query($query);

        while ($row = $result->fetch_assoc()) {
            $comment = array($row['id'] => array($row['username'] => $row['content']));
            array_push($comments, $comment);
            $comment = array();
        }

        echo json_encode($comments);
    }
}

function SaveComment() {
    $data = objectToarray(json_decode(file_get_contents('php://input')));

    session_start();

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    $login = $_SESSION['user'];
    $page = $data['page'];
    $content = $data['content'];

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status) {
        $query = "INSERT INTO comments (page, username, content) VALUES ('$page', '$login', '$content')";

        if ($sql->query($query) === True)
            echo "New record created successfully";
        else
            echo "Error: " . $query . "<br>" . $sql->error;
    }
}

function GetUserLogin() {
    session_start();

    $user = 'null';

    if (isset($_SESSION['user']))
        $user = $_SESSION['user'];

    echo $user;
}