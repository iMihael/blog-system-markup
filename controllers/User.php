<?php

class User {
    public function actionProfile($matches) {
        var_dump($matches);
        echo 'profile action';
    }
}