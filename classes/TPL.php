<?php
require_once 'vendor/Smarty/Smarty.class.php';

class TPL {
    const CONFIG_FILENAME = 'config/smarty/smarty.ini';
    
    private static $_instance = null;
    
    public static function getInstance() {
        if (self::$_instance != null) {
            return self::$_instance;
        }
        new self;
        return self::$_instance;
    }
    
    private function __construct() {
        $smarty_config = parse_ini_file(self::CONFIG_FILENAME);
        
        $smarty = new Smarty();

        $smarty->template_dir = $smarty_config['template_dir'];
        $smarty->compile_dir  = $smarty_config['compile_dir'];
        $smarty->config_dir   = $smarty_config['config_dir'];
        $smarty->cache_dir    = $smarty_config['cache_dir'];
        $smarty->debugging    = $smarty_config['debugging'];
        
        $smarty->assign([
            'messages' => $_SESSION['messages'] ?? [],
            'errors'   => $_SESSION['errors'] ?? []
        ]);
        $_SESSION['messages'] = [];
        $_SESSION['errors'] = [];
        
        self::$_instance = $smarty; 
    }
    
    public static function add_error($error) {
        if (is_array($error)) {
            $_SESSION['errors'] = array_merge($_SESSION['errors'], $error);
        } else {
            $_SESSION['errors'][] = $error;
        }
    }
    
    public static function add_message($message) {
        $_SESSION['messages'][] = $message;  
    }
}