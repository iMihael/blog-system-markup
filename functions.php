<?php

session_start();

function addUser($email, $firstName, $lastName, $password) {
    //TODO: refactor user db
    $userId = 1;
    $usersDb = fopen("db/users.db", "a+");

    if($usersDb) {
        fseek($usersDb, 0);
        while(!feof($usersDb)) {
            $userId++;
            fgets($usersDb);
        }
    }

    fseek($usersDb, 0, SEEK_END);

    $line = json_encode([
        'id' => $userId,
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'password' => sha1( $password ),
    ]);

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

function getUserById($id) {

    //TODO: refactor user db
    $usersDb = fopen("db/users.db", "r");
    if(!$usersDb) {
        return false;
    } else {
        while(!feof($usersDb)) {
            if($line = fgets($usersDb)) {
                $user = json_decode($line, true);
                if($user['id'] == $id) {
                    return $user;
                }
            }
        }
    }

    return false;
}
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