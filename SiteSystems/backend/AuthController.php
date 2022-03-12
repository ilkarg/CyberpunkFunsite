<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('DataBase/database.db');
    }
}

$_POST = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['function'])) {
    if ($_POST['function'] == 'Auth')
        Auth($_POST);
    else if ($_POST['function'] == 'Registration')
        Registration($_POST);
    else if ($_POST['function'] == 'ConfirmMail')
        ConfirmMail($_POST);
    else if ($_POST['function'] == 'ChangePassword')
        ChangePassword($_POST);
    else if ($_POST['function'] == 'CreateDatabase')
        CreateDatabase();
}

function Auth($data) {
    session_start();

    $login = $data['login'];
    $password = md5($data['password']);

    $login_ = 'null';
    $mail_ = 'null';

    try {
        $db = new MyDB();

        $query = "SELECT * FROM accounts WHERE login='$login' AND password='$password'";

        $result = $db->query($query);

        while ($row = $result->fetchArray()) {
            $login_ = isset($row['login']) ? $row['login'] : 'null';
            $mail_ = isset($row['email']) ? $row['email'] : 'null';
        }

        if ($login_ != 'null' && $mail_ != 'null') {
            if (isset($_SESSION['user']))
                unset($_SESSION['user']);

            if (isset($_SESSION['mail']))
                unset($_SESSION['mail']);

            $_SESSION['user'] = $login_;
            $_SESSION['mail'] = $mail_;
        }
    }
    catch(Exception $exception) {
        echo $exception;
    }

    echo $login_;
}


function CheckRegistration($data) {
    $registered = false;

    $login = $data['login'];
    $mail = $data['mail'];

    try {
        $db = new MyDB();

        $query = "SELECT EXISTS(SELECT * FROM accounts WHERE login='$login' OR email='$mail')";

        $result = $db->query($query);

        while ($row = $result->fetchArray(1)) {
            if (isset($row["EXISTS(SELECT * FROM accounts WHERE login='$login' OR email='$mail')"]))
                if ($row["EXISTS(SELECT * FROM accounts WHERE login='$login' OR email='$mail')"] > 0)
                    $registered = true;
        }
    }
    catch(Exception $exception) {
        echo $exception;
    }

    return $registered;
}

function Registration($data) {
    session_start();

    $registered = CheckRegistration($data);

    $login = $data['login'];
    $password = md5($data['password']);
    $mail = $data['mail'];

    $login_ = 'null';

    try {
        if (!$registered) {
            $db = new MyDB();

            $query = "INSERT INTO accounts (login, password, email) VALUES ('$login','$password','$mail')";
            $result = $db->query($query);

            if ($result !== true) {
                $login_ = $login;

                if (isset($_SESSION['user']))
                    unset($_SESSION['user']);

                if (isset($_SESSION['mail']))
                    unset($_SESSION['mail']);

                $_SESSION['user'] = $login_;
                $_SESSION['mail'] = $mail;
            }
        }
    }
    catch(Exception $exception) {
        echo $exception;
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
        $mail->Username = 'derghava7@gmail.com';
        $mail->Password = 'Ilya2012';
    
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
    $mail = $data['mail'];
    $newPassword = md5($data['newPassword']);
    $result = 'false';

    try {
        $db = new MyDB();

        $query = "UPDATE accounts SET password = '$newPassword' WHERE email = '$mail'";

        $result_ = $db->query($query);

        $result = $result_ === true ? 'false' : 'true';
    }
    catch(Exception $exception) {
        echo $exception;
    }

    echo $result;
}
