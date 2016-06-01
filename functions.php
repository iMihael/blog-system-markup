<?php

function addUser($email, $firstName, $lastName, $password) {
    //TODO: implement user id
    $line = json_encode([
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'password' => sha1( $password ),
    ]);
    $usersDb = fopen("db/users.db", "a+");
    if($usersDb) {
        fwrite($usersDb, $line . PHP_EOL);
        fclose($usersDb);
        return true;
    }

    return false;
}

function userExist($email) {
    $usersDb = fopen("db/users.db", "r");
    if(!$usersDb) {
        return false;
    } else {
        while(!feof($usersDb)) {
            $line = fgets($usersDb);
            if($line) {
                $line = json_decode($line, true);
                if($email == $line['email']) {
                    fclose($usersDb);
                    return true;
                }
            }
        }

        fclose($usersDb);
        return false;
    }
}

function checkUser($email, $password) {
    $password = sha1($password);
    $usersDb = fopen("db/users.db", "r");
    if(!$usersDb) {
        return false;
    } else {
        while(!feof($usersDb)) {
            $line = fgets($usersDb);
            if($line) {
                $line = json_decode($line, true);
                if(
                    $line["email"] == $email &&
                    $line["password"] == $password
                ) {
                    fclose($usersDb);
                    return $line;
                }
            }
        }

        fclose($usersDb);
        return false;

    }
}

function addPost($userId, $title, $body, $filePath = false) {
    $userDb = fopen("db/$userId.db", "a+");
    if(!$userDb) {
        return false;
    }
    $name = false;
    if(
        $filePath &&
        is_uploaded_file($filePath)
    ) {
        //TODO: check image (getimagesize)
        $pathInfo = pathinfo($filePath);
        $name = "img_" .
            time() . "." .
            $pathInfo['extension'];

        move_uploaded_file(
            $filePath, "img/" . $name
        );
    }

    fwrite($userDb, json_encode([
        'title' => $title,
        'body' => $body,
        'image' => $name,
        'createdAt' => date("d.m.Y H:i:s"),
    ]));
    fclose($userDb);
    return true;
}