<?php


namespace app\controllers;

use app\components\Router;
use app\components\Twig;
use app\models\User;

class Site {
    public function actionIndex() {


        $user = new User(null, 'mike@gmail.com', 'sadsad', 'dqwdqwdwq', '123', '1991-08-01');
        $user->save();


        $users = User::getUsers(5);

        echo Twig::getInstance()->render('site/index.twig', [
            'isGuest' => !isset($_SESSION['user']),
            'users' => $users
        ]);
    }

    public function actionLogin() {

        if(!empty($_POST)) {
            if(isset($_POST['email']) && isset($_POST['password'])) {
                if($user = User::checkUser($_POST['email'], $_POST['password'])) {
                    $_SESSION['user'] = true;
                    $_SESSION['firstName'] = $user->firstName;
                    $_SESSION['lastName'] = $user->lastName;
                    $_SESSION['userId'] = $user->id;
                    Router::redirect('/');
                }
            }
        } else {
            echo Twig::getInstance()->render('site/login.twig');
        }
    }

    public function actionLogout() {
        session_start();
        session_destroy();
        Router::redirect('/');
    }

    public function actionRegister() {
        if (!empty($_POST)) {
            $user = new User();
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


        echo Twig::getInstance()->render('site/register.twig');
    }
}