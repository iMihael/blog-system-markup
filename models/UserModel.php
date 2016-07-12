<?php

/**
 * Class UserModel
 * @property int $id
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $password
 */
class UserModel {
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $password;

    const DB_FILENAME = 'users.db';

    public function __construct($id, $email, $firstName, $lastName, $password) {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
    }

    public function save() {

    }


    public function __get($name) {
        if(property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("Unknown property");
        }
    }

    /**
     * @param $id
     * @return UserModel array of user models
     */
    public static function getUserById($id) {
        $usersDb = fopen("db/users.db", "r");
        if(!$usersDb) {
            return false;
        } else {
            while(!feof($usersDb)) {
                if($line = fgets($usersDb)) {
                    $user = json_decode($line, true);

                    if($user['id'] == $id) {
                        $user = new UserModel($user['id'], $user['email'], $user['firstName'], $user['lastName'], $user['password']);
                        return $user;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param int $limit amount of return objects
     * @return UserModel[] array of user models
     */
    public static function getUsers($limit) {
        $usersDb = fopen("db/users.db", "r");
        if(!$usersDb) {
            return false;
        } else {
            $i = 0;
            $users = [];
            while(!feof($usersDb) && $i < $limit) {
                if($line = fgets($usersDb)) {
                    $user = json_decode($line, true);
                    $user = new UserModel($user['id'], $user['email'], $user['firstName'], $user['lastName'], $user['password']);
                    $users[] = $user;
                }
            }
            fclose($usersDb);
            return $users;
        }
    }

    /**
     * @param $email
     * @param $password
     * @return UserModel
     */
    public static function checkUser($email, $password) {
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

                        $user = new UserModel($line['id'], $line['email'], $line['firstName'], $line['lastName'], $line['password']);
                        return $user;
                    }
                }
            }

            fclose($usersDb);
            return false;
        }
    }
}