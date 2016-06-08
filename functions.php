<?php

session_start();
$usersDb = fopen("db/users.db", "a+");

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

    $user = [
        'id' => $userId,
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'password' => sha1( $password ),
    ];
    $line = json_encode($user);

    if($usersDb) {
        fwrite($usersDb, $line . PHP_EOL);
        fclose($usersDb);
        return $user;
    }

    return false;
}

function userExist($email) {
    //TODO: refactor user db
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
    //TODO: refactor user db
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

function addPost($userId, $title, $body, $filePath = false, $fileName = false) {
    $userDb = fopen("db/$userId.db", "a+");

    if(!$userDb) {
        return false;
    }

    $name = false;

    if(
        $filePath &&
        is_uploaded_file($filePath)
    ) {
        $imageInfo = getimagesize($filePath);
        if($imageInfo) {
            $pathInfo = pathinfo($fileName);
            $name = "img_" .
                time() . "." .
                $pathInfo['extension'];

            move_uploaded_file(
                $filePath, "img/" . $name
            );
        }
    }

    fwrite($userDb, json_encode([
        'title' => $title,
        'body' => $body,
        'image' => $name,
        'createdAt' => date("d.m.Y H:i:s"),
    ]) . PHP_EOL);

    fclose($userDb);
    return true;
}

function getPostsCount($userId) {
    $posts = fopen("db/" . $userId . ".db", "r");
    $counter = 0;
    while(!feof($posts)) {
        if(fgets($posts)) {
            $counter++;
        }
    }
    fclose($posts);
    return $counter;
}

function getPostsByUserId($userId, $page = 1) {
    $pageCount = 2;
    $shift = ($page - 1) * $pageCount;
    $posts = fopen("db/" . $userId . ".db", "r");

    for($i=0;$i<$shift;$i++) {
        fgets($posts);
    }

    $results = [];
    $counter = 0;
    while(!feof($posts) && $counter < $pageCount) {
        if($line = fgets($posts)) {
            $post = json_decode($line, true);
            $results[] = $post;
        }

        $counter++;
    }

    fclose($posts);
    return $results;
}