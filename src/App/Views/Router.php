<?php

namespace App\Views;

use App\Views\Template;

class Router
{

    private static $template;

    public function __construct()
    {
        self::$template = new Template(ROOT . '/templates');
    }
    
    public static function render(string $view, string $title, array $cssFiles = [])
    {

        ob_start();
        require ROOT . '/templates/' . $view;
        $content = ob_get_clean();

        self::$template->setLayout('main');
        self::$template->setTitle($title);
        self::$template->setCssFiles($cssFiles);
        self::$template->setContent($content);

        echo self::$template->compile();
    }

    public function handle() {
        if (isset($_GET['action']) && $_GET['action'] !== '') {
            $action = $_GET['action'];
        } else {
            $action = 'home';
        }

        switch ($action) {
            case 'home':
                self::render('home.php', 'Accueil', ['index.css']);
                break;
            case 'login':
                self::render('auth/login.php', 'Connexion', ['auth.css']);
                break;
            case 'register':
                self::render('auth/register.php', 'Inscription', ['auth.css']);
                break;
            case 'logout':
                self::render('auth/logout.php', 'Deconnexion', []);
                break;
            default:
                self::render('404.php', 'Page introuvable', ['404.css']);
                break;
        }
    }
}

?>