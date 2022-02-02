<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'Converter.php';

$data = objectToarray(json_decode(file_get_contents('php://input')));

if (isset($data['function'])) {
    if ($data['function'] == 'Auth')
        Auth($data);
    else if ($data['function'] == 'Registration')
        Registration($data);
    else if ($data['function'] == 'ConfirmMail')
        ConfirmMail($data);
    else if ($data['function'] == 'ChangePassword')
        ChangePassword($data);
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
        $mail_ = 'null';

        while ($row = $result->fetch_assoc()) {
            if (isset($row['login']))
                $login_ = $row['login'];

            if (isset($row['mail']))
                $mail_ = $row['mail'];
        }

        $_SESSION['login'] = $login_;
        $_SESSION['mail'] = $mail_;

        echo $login_;
    }
}


function CheckRegistration($data) {
    $registered = false;

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    $login = $data['login'];
    $password = md5($data['password']);
    $mail = $data['mail'];

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status) {
        $query = "SELECT count(id)>0 FROM accounts WHERE BINARY login = '$login' OR BINARY email = '$mail'";
        $result_ = $sql->query($query);

        if ($result_ === True)
            $registered = 'false';
        else
            $registered = 'true';
    }

    return $registered;
}

function Registration($data) {
    session_start();

    $registered = CheckRegistration($data);

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    $login = $data['login'];
    $password = md5($data['password']);
    $mail = $data['mail'];

    $login_ = 'null';

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status && !$registered) {
        $query = "INSERT INTO accounts (login, password, email) VALUES ('$login', '$password', '$mail')";

        if ($sql->query($query) === True) {
            $login_ = $login;
            $_SESSION['login'] = $login_;
            $_SESSION['mail'] = $mail;
        }
        else
            echo "Error: " . $query . "\n" . $sql->error;
    }
    
    echo $login_;
}

function adopt($text) {
	return '=?UTF-8?B?'.base64_encode($text).'?=';
}

function ConfirmMail($data) {
    $address = $data['mail'];
    $confirmationCode = $data['confirmationCode'];

    $mail = new PHPMailer(true);

    try {
        $mail->setLanguage('ru', 'vendor/phpmailer/phpmailer/language/');
    
        $mail->SMTPDebug = 1;
    
        $mail->isSMTP();
    
        $mail->SMTPAuth = true;
    
        $mail->SMTPSecutre = 'tls';
        $mail->Port = 587;
    
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'логин от почты';
        $mail->Password = 'пароль от почты';
    
        $mail->setFrom('bot@cyberpunk-funsite.ru', 'Cyberpunk-Funsite Bot');
        
        if (is_string($address)) {
            $mail->addAddress($address);
        }
        else if (is_array($address)) {
            foreach ($address as $email) {
                $mail->addAddress($email);
            }
        }

        $mail->addCustomHeader('MIME-Version: 1.0');
        $mail->addCustomHeader('charset=ISO-8859-1');
        $mail->addCustomHeader('List-Unsubscribe', '<admin@cyberpunk-funsite.ru>, <https://cyberpunk-funsite.ru>');

        $mail->isHTML(true);
        $mail->Subject = "Mail confirmation Cyberpunk-Funsite";
        $mail->Body = "<html><h1>$confirmationCode</h1></html>";
    
        $mail->send();
    }
    catch (Exception $e) {
        echo "Error: ".$e->getMessage();
    }
}

function ChangePassword($data) {
    session_start();

    $mail = $data['mail'];
    $newPassword = md5($data['newPassword']);
    $result = 'false';

    $sql = mysqli_connect('localhost', 'root', '', '_project_isp392_');
    $status = false;

    if (!$sql) {
        echo mysqli_connect_error();
        $status = false;
    }
    else
        $status = true;

    if ($status) {
        $query = "UPDATE accounts SET password = '$newPassword' WHERE BINARY email = '$mail'";
        $result_ = $sql->query($query);

        if ($result_ === True)
            $result = 'true';
        else
            echo "Error: " . $query . "\n" . $sql->error;
    }

    echo $result;
}