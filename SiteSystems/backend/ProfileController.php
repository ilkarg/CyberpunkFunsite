<?php

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('DataBase/database.db');
    }
}

$_POST = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['function']))
    if ($_POST['function'] == 'GetUserLogin')
        GetUserLogin();
    else if ($_POST['function'] == 'CheckUserPassword')
        CheckUserPassword($_POST);
    else if ($_POST['function'] == 'ChangePassword')
        ChangePassword($_POST);
    else if ($_POST['function'] == 'ChangeNickname')
        ChangeNickname($_POST);
    else if ($_POST['function'] == 'CheckNickname')
        CheckNickname($_POST);
    else if ($_POST['function'] == 'CheckAuth')
        CheckAuth();

function GetUserLogin() {
    $user = 'null';

    session_start();
    if (isset($_SESSION['user']))
        $user = $_SESSION['user'];

    echo $user;
}

function CheckUserPassword($data) {
    $password = md5($data['password']);
    $result = 'false';

    try {
        session_start();
        if (isset($_SESSION['user'])) {
            $login = $_SESSION['user'];
            $db = new MyDB();

            $query = "SELECT * FROM accounts WHERE login = '$login' AND password = '$password'";
            $result_ = $db->query($query);

            while ($row = $result_->fetchArray()) {
                if (isset($row['login']) && isset($row['password']))
                    if ($row['login'] == $login && $row['password'] == $password)
                        $result = 'true';
            }
        }
    }
    catch(Exception $exception) {
        echo $exception;
    }

    echo $result;
}

function ChangePassword($data) {
    $password = md5($data['password']);
    $newPassword = md5($data['newPassword']);
    $result = 'false';

    try {
        session_start();
        if (isset($_SESSION['user'])) {
            $login = $_SESSION['user'];
            $db = new MyDB();

            $query = "UPDATE accounts SET password = '$newPassword' WHERE login = '$login' AND password = '$password'";
            $result_ = $db->query($query);

            $result = $result_ === true ? 'false' : 'true';

            unset($_SESSION['user']);
            unset($_SESSION['mail']);
        }
    }
    catch(Exception $exception) {
        echo $exception;
    }

    echo $result;
}

function CheckNickname($data) {
    $login = $data['login'];
    $result = 'false';

    try {
        $db = new MyDB();

        $query = "SELECT EXISTS(SELECT * FROM accounts WHERE login='$login')";

        $result_ = $db->query($query);

        $result = $result_ === true ? 'false' : 'true';
    }
    catch(Exception $exception) {
        echo $exception;
    }

    echo $result;
}

function ChangeNickname($data) {
    $username = $data['login'];
    $password = md5($data['password']);
    $result = 'false';

    try {
        session_start();
        if (isset($_SESSION['user'])) {
            $login = $_SESSION['user'];
            $db = new MyDB();

            $query = "UPDATE accounts SET login = '$username' WHERE login='$login' AND password = '$password';";
            $result_ = $db->exec($query);

            if ($result_ !== true) {
                $result = 'true';

                $query = "UPDATE comments SET username = '$username' WHERE username = '$login';";
                $result_ = $db->exec($query);

                if ($result_ !== true) {
                    unset($_SESSION['user']);
                    unset($_SESSION['mail']);
                }
                else
                    $result = 'false';
            }
        }
    }
    catch(Exception $exception) {
        echo $exception;
    }

    echo $result;
}

function CheckAuth() {
    session_start();

    $result = 'false';

    if (isset($_SESSION['user']) && isset($_SESSION['mail'])) {
        if ($_SESSION['user'] != 'null' && $_SESSION['mail'] != 'null')
            $result = 'true';
        else {
            unset($_SESSION['user']);
            unset($_SESSION['mail']);
        }
    }

    echo $result;
}
