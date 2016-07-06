<?php

class SiteController {
    public function actionRegister() {
        $files = [
            'foo',
            'bat',
            'foo',
            'bar'
        ];

        require_once __DIR__ . DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR
            . 'site' . DIRECTORY_SEPARATOR . 'register.php';
    }
}