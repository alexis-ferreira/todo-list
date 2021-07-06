<?php
require_once(__DIR__ . '/../vendor/autoload.php');
define('BASE_URL', 'http://localhost:8888/todo-list/web/');

if (isset($_GET['action'])):
    if ($_GET['action'] == 'addBook'):


    elseif ($_GET['action'] == 'addSubscriber'):


    elseif ($_GET['action'] == 'addLoaning'):


    endif;
else:

    $accueil = new Backoffice\Controller\TodoController();
    $accueil->home();
endif;