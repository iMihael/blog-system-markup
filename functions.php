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

    $path = tempnam('NOT_EXIST', 'tempDb_');

    $userDb = fopen($path, "a+");

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

    $oldDb = "db/$userId.db";

    if(file_exists($oldDb)) {
        $oldUserDb = fopen($oldDb, "r");

        while (!feof($oldUserDb)) {
            $line = fgets($oldUserDb);
            fwrite($userDb, $line);
        }

        fclose($oldUserDb);
    }
    fclose($userDb);

    rename($path, "db/$userId.db");

    return true;
}

function searchByUser($userId, $search, $page = 1) {
    $pageCount = 2;
    $results = [];
    $shift = ($page - 1) * $pageCount;
    if(file_exists("db/" . $userId . ".db")) {
        $posts = fopen("db/" . $userId . ".db", "r");

        for ($i = 0; $i < $shift;) {

            if($line = fgets($posts)){
                $post = json_decode($line, true);
                if(
                    stripos($post['title'], $search) !== false ||
                    stripos($post['body'], $search) !== false
                ) {
                    $i++;
                }
            }
        }


        $counter = 0;
        while (!feof($posts) && $counter < $pageCount) {
            if ($line = fgets($posts)) {
                $post = json_decode($line, true);
                if(
                    stripos($post['title'], $search) !== false ||
                    stripos($post['body'], $search) !== false
                ) {
                    $results[] = $post;
                    $counter++;
                }
            }


        }

        fclose($posts);
    }
    return $results;
}

function getPostsCountSearch($userId, $search) {
    $fileName = "db/" . $userId . ".db";
    $counter = 0;
    if(file_exists($fileName)) {
        $posts = fopen($fileName, "r");

        if ($posts) {
            while (!feof($posts)) {
                if ($line = fgets($posts)) {
                    $post = json_decode($line, true);

                    if(
                        stripos($post['title'], $search) !== false ||
                        stripos($post['body'], $search) !== false
                    ) {
                        $counter++;
                    }
                }
            }
            fclose($posts);
        }
    }

    return $counter;
}

function getPostsCount($userId) {
    $fileName = "db/" . $userId . ".db";
    $counter = 0;
    if(file_exists($fileName)) {
        $posts = fopen($fileName, "r");

        if ($posts) {
            while (!feof($posts)) {
                if (fgets($posts)) {
                    $counter++;
                }
            }
            fclose($posts);
        }
    }

    return $counter;
}

function getPhotosCount($userId) {
    $posts = @fopen("db/" . $userId . ".db", "r");
    $counter = 0;
    if($posts) {
        while (!feof($posts)) {
            if ($line = fgets($posts)) {
                $line = json_decode($line, true);
                if ($line['image']) {
                    $counter++;
                }
            }
        }
        fclose($posts);
    }
    return $counter;
}

function getBloggers() {
    $usersDb = fopen("db/users.db", "r");
    $results = [];

    $i = 0;
    while(!feof($usersDb) && $i < 20) {
        if($line = fgets($usersDb)) {
            $line = json_decode($line, true);
            $results[] = [
                'id' => $line['id'],
                'name' => $line['firstName']
                    . ' ' . $line['lastName'],
                'postCount' => getPostsCount($line['id']),
                'photosCount' => getPhotosCount($line['id'])
            ];
            $i++;
        }
    }
    fclose($usersDb);

    return $results;

}

function getPostsByUserId($userId, $page = 1) {
    $pageCount = 2;
    $results = [];
    $shift = ($page - 1) * $pageCount;
    if(file_exists("db/" . $userId . ".db")) {
        $posts = fopen("db/" . $userId . ".db", "r");

        for ($i = 0; $i < $shift; $i++) {
            fgets($posts);
        }


        $counter = 0;
        while (!feof($posts) && $counter < $pageCount) {
            if ($line = fgets($posts)) {
                $post = json_decode($line, true);
                $results[] = $post;
            }

            $counter++;
        }

        fclose($posts);
    }
    return $results;
}