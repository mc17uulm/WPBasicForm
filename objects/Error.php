<?php

namespace objects;

class Error
{

    public function __construct($msg)
    {
        die(json_decode(array("type" => "error", "msg" => $msg)));
    }

}