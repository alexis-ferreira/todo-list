<?php
namespace Entity;

class Todo
{

    protected $idTodo, $content, $date;

    /**
     * @return mixed
     */
    public function getIdTodo()
    {
        return $this->idTodo;
    }

    /**
     * @param mixed $idTodo
     */
    public function setIdTodo($idTodo)
    {
        $this->idTodo = $idTodo;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }


}