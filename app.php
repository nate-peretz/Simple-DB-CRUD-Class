<?php
    // Display Errors
    if( isset( $_GET['errors']) && $_GET['errors'] == 1) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting( E_ALL);
    }

    // Update php.ini
    ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] .'/app/logs/php_log.log');

    // Session
    session_start();

    // Languages
    $user_browser_lang = str_replace('-', '_', substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5));
    $lang_short = substr( $user_browser_lang, 0, 2);

    // Default language
    if( ! file_exists("./app/locale/$user_browser_lang.UTF-8/")) {
        $user_browser_lang = 'he_IL';
        $lang_short = 'he';
    }

    // Set language
    putenv("LC_ALL=$lang_short");
    setlocale( LC_ALL, "$user_browser_lang.UTF-8");
    bindtextdomain('dino', './app/locale');
    textdomain('dino');

    // Autoload- Composer + App
    require_once('vendor/autoload.php');
    require_once('app/classes/autoload.php');

    use \Helpers\Login;
    use \Helpers\URL;
    use \DB\DB;

    // DB Settings
    $host     = 'localhost';
    $charset  = 'utf8';
    $port     = 3306;
    $dbname   = 'anati';
    $user     = 'anati';
    $password = 'zxasqw12';

    // Test DB Connection
    $db_connection = DB::ConnectDB( $host, $dbname, $user, $password, $charset, $port);
    if( $db_connection !== 'connected') {
        echo '<pre>'; var_dump( $db_connection); die();
    }

    // Validate user login & session on every page
    Login::checkLoginAndURL( $_SERVER);

    // Base URL
    $url = ( isset( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    /**
     * Apply to browser pages only
     */
    if( $url) {
        // Set body classes
        $bodyClass = URL::ClassFromURL( $url);

        // Set base path url
        $base_path_url = URL::basePathURL( $url);
    }

    $app_name = _('Dino');
    $app_desc = _('Admin panel');
