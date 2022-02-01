<?php


class Handler
{
    private $controller;
    private $method;

    /**
     * @param $controller
     * @param $method
     */
    public function __construct($controller, $method)
    {
        $this->controller = $controller;
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }
}