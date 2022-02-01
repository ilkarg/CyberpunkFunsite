<?php

require_once 'Converter.php';

$data = objectToarray(json_decode(file_get_contents('php://input')));

if (isset($data['function']))
    if ($data['function'] == 'GetUserLogin')
        GetUserLogin();
    else if ($data['function'] == 'CheckUserPassword')
        CheckUserPassword($data);
    else if ($data['function'] == 'ChangePassword')
        ChangePassword($data);
    else if ($data['function'] == 'ChangeNickname')
        ChangeNickname($data);
    else if ($data['function'] == 'CheckNickname')
        CheckNickname($data);

function GetUserLogin() {
    session_start();

    $user = 'null';

    if (isset($_SESSION['user']))
        $user = $_SESSION['user'];

    echo $user;
}

function CheckUserPassword($data) {
    session_start();

    $password = md5($data['password']);
    $result = 'false';
    $login = '';

    if (isset($_SESSION['user'])) {
        $login = $_SESSION['user'];

        $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
        $status = false;

        if (!$sql) {
            echo mysqli_connect_error();
            $status = false;
        }
        else
            $status = true;

        if ($status) {
            $query = "SELECT * FROM accounts WHERE BINARY login = '$login' AND BINARY password = '$password'";
            $result_ = $sql->query($query);

            while ($row = $result_->fetch_assoc())
                if (isset($row['login']) && isset($row['password']))
                    if ($row['login'] == $login && $row['password'] == $password)
                        $result = 'true';
        }
    }

    echo $result;
}

function ChangePassword($data) {
    session_start();

    $password = md5($data['password']);
    $newPassword = md5($data['newPassword']);
    $result = 'false';
    $login = '';

    if (isset($_SESSION['user'])) {
        $login = $_SESSION['user'];

        $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
        $status = false;

        if (!$sql) {
            echo mysqli_connect_error();
            $status = false;
        }
        else
            $status = true;

        if ($status) {
            $query = "UPDATE accounts SET password = '$newPassword' WHERE BINARY login = '$login' AND BINARY password = '$password'";
            $result_ = $sql->query($query);

            if ($result_ === True)
                $result = 'true';
            else
                echo "Error: " . $query . "\n" . $sql->error;
        }
    }

    echo $result;
}

function CheckNickname($data) {
    $login = $data['login'];
    $result = 'fal';

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status) {
        $query = "SELECT count(id)>0 FROM accounts WHERE BINARY login = '$login'";
        $result_ = $sql->query($query);

        if ($result_ === True)
            $result = 'false';
        else
            $result = 'true';
    }

    echo $result;
}

function ChangeNickname($data) {
    session_start();

    $username = $data['login'];
    $password = md5($data['password']);
    $result = 'false';
    $login = '';

    if (isset($_SESSION['user'])) {
        $login = $_SESSION['user'];

        $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
        $status = false;

        if (!$sql) {
            echo mysqli_connect_error();
            $status = false;
        }
        else
            $status = true;

        if ($status) {
            $query = "UPDATE accounts SET login = '$username' WHERE BINARY login = '$login' AND BINARY password = '$password'";
            $result_ = $sql->query($query);

            if ($result_ === True) {
                $result = $result_;

                $query = "UPDATE comments SET username = '$username' WHERE BINARY username = '$login'";
                $result_ = $sql->query($query);

                if ($result_ === True) {
                    $result = 'true';
                    $_SESSION['user'] = $username;
                }
                else {
                    echo "Error: " . $query . "\n" . $sql->error;
                }
            }
            else {
                echo "Error: " . $query . "\n" . $sql->error;
            }
        }
    }

    echo $result;
}