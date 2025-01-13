<?php

namespace App\Views;

use App\Controllers\Auth\Auth;

class Router
{

    private static $template;

    public function __construct()
    {
        self::$template = new Template(ROOT . '/templates');
    }
    
    public static function render(string $view, string $title, array $cssFiles = [])
    {
        self::renderWithTemplate($view, $title, 'main', $cssFiles);
    }

    public static function renderWithTemplate(string $view, string $title, string $layout, array $cssFiles = [])
    {

        ob_start();
        require ROOT . '/templates/' . $view;
        $content = ob_get_clean();

        self::$template->setLayout($layout);
        self::$template->setTitle($title);
        self::$template->setCssFiles($cssFiles);
        self::$template->setContent($content);

        echo self::$template->compile();
    }

    public function execute() {
        if (isset($_GET['action']) && $_GET['action'] !== '') {
            $action = $_GET['action'];
        } else {
            $action = 'home';
        }

        switch ($action) {
            case 'home':
                self::render('home.php', 'Accueil', ['index.css']);
                break;
            case "planning":
                Auth::checkUserLoggedIn();
                self::render('planning.php', 'Planning', ['planning.css', 'navigation.css']);
                break;
            case 'login':
                self::render('auth/login.php', 'Connexion', ['form.css']);
                break;
            case 'register':
                self::render('auth/register.php', 'Inscription', ['form.css']);
                break;
            case 'logout':
                self::render('auth/logout.php', 'Deconnexion', []);
                break;
            case 'creation_cours':
                self::renderWithTemplate('admin/creation_cours_p.php', "Création d'un cours", 'main', ['form.css', 'full-form.css']);
                break;
            default:
                self::render('404.php', 'Page introuvable', ['404.css']);
                break;
        }
    }
}

?>