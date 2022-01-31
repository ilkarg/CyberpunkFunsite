<?php

require_once 'Converter.php';

$data = objectToarray(json_decode(file_get_contents('php://input')));

if (isset($data['function'])) {
    if ($data['function'] == 'Auth')
        Auth($data);
    else if ($data['function'] == 'Registration')
        Registration($data);
}

function Auth($data) {
    session_start();

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    $login = $data['login'];
    $password = md5($data['password']);

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status) {
        $query = "SELECT * FROM accounts WHERE BINARY login = '$login' AND BINARY password = '$password'";
        $result = $sql->query($query);

        $login_ = 'null';

        while ($row = $result->fetch_assoc())
            if (isset($row['login']))
                $login_ = $row['login'];

        $_SESSION['login'] = $login_;
        echo $login_;
    }
}


function CheckRegistration($data) {
    $registered = false;

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    $login = $data['login'];
    $password = md5($data['password']);

    $login_ = 'null';

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status) {
        $query = "SELECT * FROM accounts WHERE BINARY login = '$login' AND BINARY password = '$password'";
        $result = $sql->query($query);

        while ($row = $result->fetch_assoc())
            if (isset($row['login']))
                $login_ = $row['login'];
    }

    if ($login_ != 'null')
        $registered = true;

    return $registered;
}

function Registration($data) {
    session_start();

    $registered = CheckRegistration($data);

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    $login = $data['login'];
    $password = md5($data['password']);

    $login_ = 'null';

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status && !$registered) {
        $query = "INSERT INTO accounts (login, password) VALUES ('$login', '$password')";

        if ($sql->query($query) === True) {
            $login_ = $login;
            $_SESSION['login'] = $login_;
        }
        else
            echo "Error: " . $query . "\n" . $sql->error;
    }
    
    echo $login_;
}