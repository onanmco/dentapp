<?php

namespace core;

use Exception;

abstract class Controller
{
    /**
     * Adds 'Action' suffix to the current action
     * 
     * Calls first $this->before() function can be used for generic actions 
     * that must be handled before every action (e.g. session validation)
     * 
     * After calls current action
     * 
     * And finally calls $this->after() function can be used for generic actions 
     * that must be handled after every action (e.g. session management)
     * 
     * @param string $name
     * @param array $arguments
     * 
     * @return void
     * 
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $name .= 'Action';
        if (method_exists($this, $name)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $name], $arguments);
                $this->after();
            }
        } else {
            throw new Exception("Function: \"$name\" (in class: " . get_class($this) . ") cannot be found.", 404);
        }
    }

    /**
     * URI parameters that passed by route
     * 
     * @var array $params 
     */
    protected $args = [];

    /**
     * Constructor is for passing URI parameters to related controller
     * 
     * @param array 
     * 
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * This function will be called before every single controller function call.
     * Must be overridden at the derived Controller class.
     * 
     * @return void
     */
    protected function before()
    {
    }

    /**
     * This function will be called after every single controller function call.
     * Must be overridden at the derived Controller class.
     * 
     * @return void
     */
    protected function after()
    {
    }
    
}