<?php

  class Model_Globals
{


}

class Controller_Globals extends Model_Globals
{
    public $core;

    public function __construct()
    {
        global $core; $this->core = $core;
    }


}
