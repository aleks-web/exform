<?php

class ExformTheme {
    private $path;
    private $name;

    public function __construct($name = null, $path = null) {
        $this->path = $path;
        $this->name = $name;
    }

    public function getPath() {
        return $this->path;
    }

    public function getName() {
        return $this->name;
    }

    public function requireForm() {
        $form_file = realpath($this->getPath() . '/form.php');

        if (file_exists($form_file)) {
            require $form_file;
        }
    }

    public function getFormContent() {
        $form_file = realpath($this->getPath() . '/form.php');

        $file_handle = fopen($form_file, 'r'); // Открываем файл, $file_handle теперь - ресурс
        if ($file_handle) {
            echo "Файл открыт, ресурс: " . $file_handle . "\n"; // Выведет "Файл открыт, ресурс: Resource id #5"
            $content = fread($file_handle, 1024); // Читаем из ресурса
            echo "Содержимое: " . $content . "\n";
            fclose($file_handle); // Закрываем ресурс
        } else {
            echo "Не удалось открыть файл.";
        }
    }
}