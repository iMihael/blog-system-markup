<?php

class SiteController {
    public function actionIndex() {
        $users = UserModel::getUsers(5);

        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'site' . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function actionLogin() {

        if(!empty($_POST)) {
            if(isset($_POST['email']) && isset($_POST['password'])) {
                if($user = UserModel::checkUser($_POST['email'], $_POST['password'])) {
                    $_SESSION['user'] = true;
                    $_SESSION['firstName'] = $user->firstName;
                    $_SESSION['lastName'] = $user->lastName;
                    $_SESSION['userId'] = $user->id;
                    header("Location: /");
                }
            }
        } else {

            require_once __DIR__ . DIRECTORY_SEPARATOR .
                '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
                . 'site' . DIRECTORY_SEPARATOR . 'login.php';
        }
    }
}