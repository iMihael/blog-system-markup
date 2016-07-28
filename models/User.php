<?php


namespace app\models;
use app\components\MySQLConnector;

/**
 * Class UserModel
 * @property int $id
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $password
 * @property \DateTime $createdAt
 */
class User {
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $password;
    private $createdAt;

    const DB_FILENAME = 'users.db';

    public function __construct($id = null, $email = null, $firstName = null, $lastName = null, $password = null, $createdAt = null) {
        $this->id = intval($id);
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        if(is_string($createdAt)) {
            $createdAt = new \DateTime($createdAt);
        }
        if($createdAt instanceof \DateTime) {
            $this->createdAt = $createdAt;
        }
    }

    public function save() {
        if($this->id) {
            $sql = 'UPDATE user SET email = :e, firstName = :fN, lastName = :lN, password = :pwd, createdAt = :createdAt';
        } else {
            $sql = 'INSERT INTO user (email, firstName, lastName, password, createdAt) VALUES (:e, :fN, :lN, :pwd, :createdAt)';
            $this->createdAt = new \DateTime();
        }

        $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
        $vars = [
            ':e' => $this->email,
            ':fN' => $this->firstName,
            ':lN' => $this->lastName,
            ':pwd' => sha1($this->password),
            ':createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
        if($statement->execute($vars)) {
            if (!$this->id) {
                $this->id = intval(MySQLConnector::getInstance()->getPDO()->lastInsertId());
            }

            return true;
        } else {
            var_dump($statement->errorInfo());
        }

        return false;
    }

    public function delete() {
        if($this->id) {
            $sql = "DELETE FROM user WHERE (id = :id)";
            $statement = MySQLConnector::getInstance()->getPDO()->prepare($sql);
            if($statement->execute([
                ':id' => $this->id,
            ])) {
                $this->id = null;
                return true;
            }
        }

        return false;
    }

    public function load($array) {
        foreach($array as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $array[$key];
            }
        }
    }

    public function validate() {
        $required = [
            'email',
            'firstName',
            'lastName',
            'email',
            'password'
        ];

        foreach($required as $key) {
            if(!$this->$key) {
                return false;
            }
        }

        return true;
    }

    public function __get($name) {
        if(property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new \Exception("Unknown property");
        }
    }

    /**
     * @param $id
     * @return User user model
     */
    public static function getUserById($id) {
        $statement = MySQLConnector::getInstance()->getPDO()->prepare('SELECT * FROM user WHERE id = :id');
        $statement->execute([
            ':id' => $id,
        ]);
        if($user = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $user = new User($user['id'], $user['email'], $user['firstName'], $user['lastName'], $user['password'], $user['createdAt']);
            return $user;
        }

        return null;
    }

    /**
     * @param int $limit amount of return objects
     * @return User[] array of user models
     */
    public static function getUsers($limit) {
        $statement = MySQLConnector::getInstance()->getPDO()->prepare('SELECT * FROM user WHERE LIMIT :limit');
        $statement->execute([
            ':limit' => $limit,
            //':active' => 1,
        ]);

        $users = [];

        while($user = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $user = new User($user['id'], $user['email'], $user['firstName'], $user['lastName'], $user['password'], $user['createdAt']);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * @param $email
     * @param $password
     * @return User
     */
    public static function checkUser($email, $password) {
        $password = sha1($password);
        $statement = MySQLConnector::getInstance()->getPDO()->prepare('SELECT * FROM user WHERE email = :email AND password = :pwd');
        $statement->execute([
            ':email' => $email,
            ':pwd' => $password,
        ]);
        if($user = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $user = new User($user['id'], $user['email'], $user['firstName'], $user['lastName'], $user['password'], $user['createdAt']);
            return $user;
        }

        return null;
    }
}