<?php

namespace App\Router;

use App\Exception\NotFoundException;
use App\Helper\RequestHelper;

class Router
{
    /**
     * @var Router|null
     */
    public static $router = null;
    private $routes;
    private $request;
    private $target;
    private $controller;
    private $method;

    public function __construct(RequestHelper $request)
    {
        $this->request = $request;
    }

    /**
     * @param RequestHelper $request
     *
     * @return Router|null
     */
    public static function routerInstance($request)
    {
        if (self::$router === null) {
            self::$router = new self($request);
        }

        return self::$router;
    }

    /**
     * Set routes
     * @param $method
     * @param $target
     * @param $uri
     */
    public function map($method, $target, $uri)
    {
        $method = explode('@', $method);
        $this->routes[$uri] = [
            'method' => $method,
            'target' => $target
        ];
    }

    /**
     * Check if request method is allowed
     * @throws NotFoundException
     */
    public function checkMethod()
    {
        if (!in_array($this->request->getRequestMethod(),
            $this->routes[$this->request->getUri()]['method'])) {
            throw new NotFoundException();
        }
    }

    /**
     * Check if route exist
     * @throws NotFoundException
     */
    public function routeExist()
    {
        if (!array_key_exists($this->request->getUri(), $this->routes)) {
            throw new NotFoundException();
        }

        $this->setTarget($this->routes[$this->request->getUri()]['target']);
    }

    /**
     * Set Controller and Method
     */
    public function prepareAction()
    {
        $helperArr = explode("@", $this->target);

        $this->setController($helperArr[0]);
        $this->setMethod($helperArr[1]);
    }

    /**
     * @throws NotFoundException
     */
    public function callMethod()
    {
        $controllerClassName = 'App\\Controller\\' . ucfirst($this->getController()) . "Controller";
        if (!class_exists($controllerClassName)) {
            throw new NotFoundException();
        }
        $methodName = $this->getMethod();
        $controller = new $controllerClassName();
        if (!method_exists($controller, $methodName)) {
            throw new NotFoundException();
        }

        $controller->$methodName();
    }

    /**
     * @throws NotFoundException
     */
    public function startAction()
    {
        $this->routeExist();
        $this->checkMethod();
        $this->prepareAction();
        $this->callMethod();
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string|array
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }
}