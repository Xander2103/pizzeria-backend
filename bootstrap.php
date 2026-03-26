<?php
//bootstrap 
//Alles wat één keer moet voorbereid worden voordat je controllers draaien komt hier. (autoload)

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/view/templates');

$twig = new Environment($loader, [
    'cache' => false, 
]);