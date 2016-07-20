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
                    Router::redirect('/');
                }
            }
        } else {

            require_once __DIR__ . DIRECTORY_SEPARATOR .
                '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
                . 'site' . DIRECTORY_SEPARATOR . 'login.php';
        }
    }

    public function actionLogout() {
        session_start();
        session_destroy();
        Router::redirect('/');
    }

    public function actionRegister() {
        if (!empty($_POST)) {
            $user = new UserModel();
            $user->load($_POST);
            if($user->validate()) {
                if($user->save()) {
                    $_SESSION['user'] = true;
                    $_SESSION['firstName'] = $user->firstName;
                    $_SESSION['lastName'] = $user->lastName;
                    $_SESSION['userId'] = $user->id;
                    Router::redirect('/');
                }
            }
        }


        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'site' . DIRECTORY_SEPARATOR . 'register.php';
    }
}