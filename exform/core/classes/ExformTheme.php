<?php

class ExformTheme {
    private $path;
    private $name;

    public function __construct($path = '') {
        $this->path = $path;
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
}