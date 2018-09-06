<?php

namespace objects;

class Category
{

    private $type;
    private $info;

    public function __construct($type, $info)
    {
        $this->type = $type;
        $this->info = $info;

    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }



}