<?php

class ExformTheme {
    private $path;
    private $name;
    private $config;

    public function __construct($name = null, $path = null) {
        $this->path = $path;
        $this->name = $name;
        $this->config = $this->getConfigFromFile();

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
        $file = realpath($this->getPath() . '/' . $file);

        if (file_exists($file)) {
            $content = file_get_contents($file);
            return htmlspecialchars($content);
        }

        return null;
    }

    private function getConfigFromFile() {
        $config = [];
        $config_file = realpath($this->getPath() . '/config.ini');
        if (file_exists($config_file)) {
            $config = parse_ini_file($config_file);
        }

        return $config;
    }

    public function getConfig() {
        return $this->config;
    }

    public function toArray() {
        $data = [];
        $data['path'] = $this->path;
        $data['name'] = $this->name;
        $data['config'] = $this->config;

        return $data;
    }
}