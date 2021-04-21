<?php

namespace App\Models;


class Tag extends CoreModel
{
    protected $name;
    protected static $tableName = 'tag';

    public function insert()
    {
        //@TODO
        echo 'TODO INSERT';
        echo __FILE__.':'.__LINE__; exit();
    }

    public function update()
    {
        //@TODO
        echo 'TODO UPDATE';
        echo __FILE__.':'.__LINE__; exit();
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
