<?php

namespace app\controllers;

use app\components\Router;
use app\models\Post;

class Blog {
    public function actionIndex($params) {

        $id = $params[1];

        $posts = Post::findPostsByUser($id);

        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'blog' . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function actionAdd() {


        $post = new Post();
        $post->load($_POST, $_FILES);


        if($post->validate()) {
            $post->setUserId($_SESSION['userId']);
            $post->save();
            Router::redirect("/blog/index/" . $_SESSION['userId']);
        }


        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'blog' . DIRECTORY_SEPARATOR . 'add.php';
    }
}