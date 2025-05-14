<?php
namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View{
    private $twig;

    public function __construct(){
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
    }
    public function render(string $template, array $data = []): string{
        return $this->twig->render($template, $data);
    }
}