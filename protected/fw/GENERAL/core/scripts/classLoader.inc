<?php

    spl_autoload_register(array(ClassLoader::getInstance(), 'loadClass'));

/**
 * Universal autoloader class.
 * @desc Universal autoloader class.
 *
 * @package Core
 * @version 1.0
 * @copyright Copyright (c) 2012 Serenity Media
 * @author  Victor Nițu
 * @license AGPLv3 {@link http://www.gnu.org/licenses/agpl-3.0.txt}
 */
class ClassLoader {

    private static $saveFile;

    /* singleton */
    private static $instance;

    /* stores a className -> filePath map */
    private $classList;
    /* tells whether working from saved file */
    private $refreshed;


    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new ClassLoader();
        }
        return self::$instance;
    }

    private function __construct() {
        self::$saveFile = VAR_PATH.'ClassLoader.save.php';
        $this->initClassList();
    }

    public function loadClass($className)
    {
        $packageClass = str_replace('_', '/', $className);
        //echo $packageClass . '.php ... ';

        if (stream_resolve_include_path($packageClass . '.php')) {
            //echo "in path.\n<br>";
            include_once $packageClass . '.php';
            return true;
        } elseif ( !array_key_exists($className, $this->classList) && !$this->refreshed ) {
            //echo "trying class list.\n<br>";
            $this->refreshClassList();
        }
        if (isset($this->classList[$className]) && substr($this->classList[$className], -4, 4) === ".php") {
            include_once $this->classList[$className] ;
        } else {
            return false;
        }
    }

    private function initClassList()
    {


        if (file_exists(self::$saveFile)) {
            require_once(self::$saveFile);
            $this->refreshed = FALSE;
        } else {
            $this->refreshClassList();
        }
    }

    private function refreshClassList()
    {
        $this->classList = $this->scanDirectory(FW_INC_PATH);
                         //+ $this->scanDirectory('/usr/share/php');
        $this->refreshed = TRUE;

        $this->saveClassList();
    }


    private function openFile()
    {
        if (($handle = fopen(self::$saveFile, "w+") === FALSE))
            throw new Exception("File cannot be opened for writing!");
        chmod(self::$saveFile, 0777);
        return $handle;
    }

    private function saveClassList()
    {

        try {
            $handle = $this->openFile();
        }
        catch (Exception $e) {
            echo "Class loader exception: <br/>\n".$e->getMessage();
        }

        $content = "<?php \r\n";
        foreach($this->classList as $class => $path) {
            $content .= '$this->classList' . "['" . $class . "'] = '" . $path . "';\r\n";
        }

        $content .= '?>';
        file_put_contents(self::$saveFile, $content);
    }

    private function scanDirectory ($directory) {
        // strip closing '/'
        if (substr($directory, -1) == '/') {
                $directory = substr($directory, 0, -1);
        }

        if (!file_exists($directory) || !is_dir($directory) ||
                    !is_readable($directory) ||
                    (is_link($directory) && !is_dir(readlink($directory)))
        ) {
            return array();
            throw new Exception ('Cannot enter dir!');
        }

        $dirH = opendir($directory);
        $scanRes = array();

        while(($file = readdir($dirH)) !== FALSE) {

            // skip pointers
            if ( strcmp($file , '.') == 0 || strcmp($file , '..') == 0 || strcmp($file , '.svn') == 0) {
                continue;
            }

            $path = $directory . '/' . $file;

            if (!is_readable($path)) {
                continue;
            }

            // recursion
            if (is_dir($path) && !is_link($path)) {
                $scanRes = array_merge($scanRes, $this->scanDirectory($path));
            } elseif (is_link($path) && is_dir($path)){
                $scanRes = array_merge($scanRes, $this->scanDirectory(realpath($path)));
            } elseif (is_file($path)) {
                $className = explode('.', $file);
                if ( $className[count($className)-1] == 'php')  {
                    $scanRes[$className[0]] = realpath($path);
                }
            }
        }

        return $scanRes;
    }

}
?>
