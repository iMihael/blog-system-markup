<?php

class BlogController {
    public function actionIndex($params) {

        $id = $params[1];

        $posts = PostModel::findPostsByUser($id);

        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'blog' . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function actionAdd() {


        $post = new PostModel();
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