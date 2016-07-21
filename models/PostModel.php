<?php

/**
 * Class PostModel
 *
 * @property string $title
 * @property string $body
 * @property string $image
 * @property string $createdAt
 */
class PostModel {
    private $id;
    private $title;
    private $body;
    private $image;
    private $createdAt;
    private $userId;

    const PER_PAGE = 2;

    public function __construct($id = null, $title = null, $body = null, $image = null, $createdAt = null, $userId = null) {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->image = $image;
        $this->createdAt = $createdAt;
        $this->userId = $userId;
    }

    public function setUserId($value) {
        $this->userId = $value;
    }

    public function save() {
        $path = tempnam('NOT_EXIST', 'tempDb_');

        $userDb = fopen($path, "a+");

        if(!$userDb) {
            return false;
        }

        $name = false;

        if(
            $this->image['error'] == 0 &&
            is_uploaded_file($this->image['tmp_name'])
        ) {
            $imageInfo = getimagesize($this->image['tmp_name']);
            if($imageInfo) {
                $pathInfo = pathinfo($this->image['name']);

                $name = "img_" .
                    time() . "." .
                    $pathInfo['extension'];

                //TODO: implement imagick
                $img = new Imagick($this->image['tmp_name']);
                $img->thumbnailImage(100, 0);
                $img->writeImage("img/thumb_" . $name);

                move_uploaded_file(
                    $this->image['tmp_name'], "img/" . $name
                );



            }
        }

        fwrite($userDb, json_encode([
                'title' => $this->title,
                'body' => $this->body,
                'image' => $name,
                'createdAt' => date("d.m.Y H:i:s"),
            ]) . PHP_EOL);

        $oldDb = "db/".$this->userId.".db";

        if(file_exists($oldDb)) {
            $oldUserDb = fopen($oldDb, "r");

            while (!feof($oldUserDb)) {
                $line = fgets($oldUserDb);
                fwrite($userDb, $line);
            }

            fclose($oldUserDb);
        }
        fclose($userDb);

        rename($path, "db/".$this->userId.".db");




        return true;
    }

    public function __get($name) {
        if(property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("Unknown property");
        }
    }

    /**
     * @param $userId
     * @param int $page
     * @return PostModel[]
     */
    public static function findPostsByUser($userId, $page = 1) {

        //$sql = "SELECT * FROM post WHERE userId = :userId OFFSET :offset LIMIT :limit";
        $shift = ($page - 1) * self::PER_PAGE;
        $sql = "SELECT * FROM post WHERE userId = :userId LIMIT :limit OFFSET :offset";
        $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        if($statement->execute([
            ':userId' => $userId,
            ':limit' => self::PER_PAGE,
            ':offset' => $shift,
        ])) {

            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $posts = [];
            foreach($rows as $row) {
                $post = new PostModel(
                    $row['id'],
                    $row['title'], $row['body'],
                    $row['image'], $row['createdAt'], $row['userId']);
                $posts[] = $post;
            }
        }

        $results = [];



        if(file_exists("db/" . $userId . ".db")) {
            $posts = fopen("db/" . $userId . ".db", "r");

            for ($i = 0; $i < $shift; $i++) {
                fgets($posts);
            }


            $counter = 0;
            while (!feof($posts) && $counter < self::PER_PAGE) {
                if ($line = fgets($posts)) {
                    $post = json_decode($line, true);
                    $post = new PostModel($post['title'], $post['body'], $post['image'], $post['createdAt']);
                    $results[] = $post;
                }

                $counter++;
            }

            fclose($posts);
        }

        return $results;
    }

    public function load($post, $files) {
        $fields = [
            'title',
            'body',
            'image',
        ];

        foreach($fields as $field) {
            if(isset($post[$field])) {
                $this->$field = $post[$field];
            }

            if(isset($files[$field])) {
                $this->$field = $files[$field];
            }
        }
    }

    public function validate() {
        $required = [
            'title',
            'body',
        ];

        foreach($required as $req) {
            if(!$this->$req) {
                return false;
            }
        }

        return true;
    }
}