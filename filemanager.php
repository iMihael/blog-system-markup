<?php

class FileManager {
    private static $instance;
    private function __construct() {

    }
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __clone() { /* ... @return Singleton */ }  // Защищаем от создания через клонирование
    private function __wakeup() { /* ... @return Singleton */ }  // Защищаем от создания через unserialize

    public function getFiles($path = __DIR__) {
        $files = scandir($path);
        unset($files[0]);
        $result = [];

        foreach($files as $file) {
            $filePath = $path .
                DIRECTORY_SEPARATOR . $file;

            $result[$file] = [
                'absolute' => $filePath,
                'is_dir' => is_dir($filePath)
            ];
        }

        return $result;
    }


}

if(isset($_GET['path'])) {
    $files = FileManager::getInstance()
        ->getFiles($_GET['path']);
} else {
    $files = FileManager::getInstance()
        ->getFiles();
}

?>

<ul>
    <?php foreach($files as $fileName => $file): ?>
    <li>
        <?php if($file['is_dir']): ?>
        <a href="?path=<?php echo $file['absolute'] ?>">
            <?php echo $fileName ?>
        </a>
        <?php else: ?>
            <span><?php echo $fileName ?></span>
        <?php endif ?>
    </li>
    <?php endforeach ?>
</ul>