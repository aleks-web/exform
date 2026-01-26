<?php



class ExformTheme {
    private $path;
    private $name;
    private $config;
    private $isCurrent;
    private static $themes = [];

    public function __construct($name = null, $path = null) {
        $this->path = $path;
        $this->name = $name;
        $this->config = $this->getConfigFromFile();
        $this->isCurrent = false;
        array_push(self::$themes, $this);
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
            ob_start();
            require $form_file;
            $result = ob_get_clean();
            return $result;
        }
    }

    public function requireMsg($bool = true) {
        $msgFile = $bool ? 'success_msg' : 'error_msg';
        $form_file = realpath($this->getPath() . '/' . $msgFile . '.php');

        if (file_exists($form_file)) {
            ob_start();
            require $form_file;
            $result = ob_get_clean();
            return $result;
        }
    }

    public function getContentFromFile($file) {
        $file = realpath($this->getPath() . '/' . $file);

        if (file_exists($file)) {
            $content = file_get_contents($file);
            return $content;
        }

        return null;
    }

    private function getConfigFromFile() {
        $themeConfig = [];
        $config_file = realpath($this->getPath() . '/config.ini');
        if (file_exists($config_file)) {
            $themeConfig = parse_ini_file($config_file);
        }

        if (isset($GLOBALS['config'])) {
            $themeConfig = array_merge($GLOBALS['config'], $themeConfig);
        }

        return $themeConfig;
    }

    public function getConfig() {
        return $this->getConfigFromFile();
    }

    public function toArray() {
        $data = [];
        $data['path'] = $this->path;
        $data['name'] = $this->name;
        $data['config'] = $this->config;
        $data['isCurrent'] = $this->isCurrent;

        return $data;
    }

    public static function getThemeByName($themeName) {
        foreach (self::getAllThemes() as $theme) {
            if ($theme->getName() === $themeName) {
                return $theme;
            }
        }

        return null;
    }

    public static function setCurrentThemeByName($themeName) {
        $theme = ExformTheme::getThemeByName($themeName);
        $theme->isCurrent = true;
    }

    public static function getCurrentTheme() {
        foreach (self::getAllThemes() as $theme) {
            if ($theme->isCurrent()) {
                return $theme;
            }
        }
        return null;
    }

    public static function getAllThemes() {
        return self::$themes;
    }

    public static function getAllThemesArray() {
        $themes = [];
        foreach (self::getAllThemes() as $theme) {
            $themes[$theme->getName()] = $theme->toArray();
        }

        return $themes;
    }

    public function isCurrent() {
        return $this->isCurrent;
    }
}