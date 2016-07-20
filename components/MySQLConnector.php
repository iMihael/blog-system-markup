<?php

class MySQLConnector {
    public function __construct() {
        try {
            $dbh = new PDO("mysql:host=localhost;dbname=school2", 'root', 'didiom17');
        } catch (Exception $error) {
            var_dump($error->getMessage());
        }

        $statement = $dbh->prepare("INSERT INTO user (first_name, last_name, password, email) VALUES
(:fName, :lName, :pwd, :email)");

        $statement->execute([
            ':fName' => 'Vasia',
            ':lName' => 'Puplin',
            ':pwd' => md5('12344'),
            ':email' => 'mike12222@gmail.com'
        ]);

        var_dump($dbh->lastInsertId());

        $statement->execute([
            ':fName' => 'Andrey',
            ':lName' => 'Tachilin',
            ':pwd' => md5('12345'),
            ':email' => 'andr23213213ew@gmail.com'
        ]);




    }
}