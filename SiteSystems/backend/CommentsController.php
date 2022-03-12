<?php

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('DataBase/database.db');
    }
}

$_POST = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['function'])) {
    if ($_POST['function'] == 'LoadComments')
        LoadComments();
    else if ($_POST['function'] == 'GetUserLogin')
        GetUserLogin();
    else if ($_POST['function'] == 'SaveComment')
        SaveComment($_POST);
}

function LoadComments() {
    $comment_array = array();
    $comments_array = array();

    try {
        $db = new MyDB();

        $query = "SELECT * FROM comments WHERE page='test'";

        $result = $db->query($query);

        while ($row = $result->fetchArray()) {
            $comment_array = array($row['id'] => array($row['username'] => $row['content']));
            array_push($comments_array, $comment_array);
        }
    }
    catch(Exception $exception) {
        echo $exception;
    }

    echo json_encode($comments_array);
}

function SaveComment($data) {
    session_start();

    $login = $_SESSION['user'];
    $page = $data['page'];
    $content = $data['content'];

    try {
        $db = new MyDB();

        $query = "INSERT INTO comments (page, username, content) VALUES ('$page','$login','$content')";

        $result = $db->exec($query);

        if ($result !== true)
            echo "New record created successfully";
        else
            echo "Khm, error :/";
    }
    catch(Exception $exception) {
        echo $exception;
    }
}

function GetUserLogin() {
    session_start();

    $user = 'null';

    if (isset($_SESSION['user']))
        $user = $_SESSION['user'];

    echo $user;
}
