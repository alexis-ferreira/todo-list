<?php
namespace Backoffice\Controller;

use Controller\Controller;

class TodoController extends Controller
{

    public function home()
    {

        $todos = $this->getRepository("Todo");

        $this->render('layout.php', 'home.php', array(

            "title" => "Todo List",
            "todos" => $todos
        ));
    }

    public function addTodo()
    {
        $todos = $this->getRepository("Todo");

        $this->render('layout.php', 'addTodo.php', array(

            "title" => "Add Todo",
            "todos" => $todos
        ));
    }
}