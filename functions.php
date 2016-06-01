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