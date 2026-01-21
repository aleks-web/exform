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

    public function getContentFromFile($file) {
        $form_file = realpath($this->getPath() . '/' . $file);

        if (file_exists($form_file)) {
            $content = file_get_contents($form_file);
            return htmlspecialchars($content);
        }
    }
}