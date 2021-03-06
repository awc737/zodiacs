<?php

class Bootstrap {

    function __construct() {

        require 'config.php';
        require 'libraries/model.php';
        require 'libraries/view.php';
        require 'libraries/controller.php';
        require 'libraries/database.php';

        // Controller
        if (!isset($_GET['url'])) {
            $url[0] = 'home';
        } else {
            $url = explode('/', rtrim($_GET['url'], '/'));
        }
        $controllerName = $url[0];
        $controller = $this->getController($controllerName);
        $controller = new $controller;

        // Task and parameters
        if (!isset($url[1])) {
            $url[1] = 'index';
        }
        $taskName = $url[1];
        $params = array();
        for ($i = 0; $i < count($url); $i++) {
            if ($i > 1) {
                $params[] = $url[$i];
            }
        }
        $this->getTask($controller, $taskName, $params);

    }

    function getController($controllerName = null) {
        $controllerPath = 'controllers/' . $controllerName . '.php';
        if (!file_exists($controllerPath)) {
            $controllerName = 'error';
        }
        require 'controllers/' . $controllerName . '.php';
        $controller = $controllerName.'Controller';
        return $controller;
    }

    function getTask($controller = null, $taskName = null, $params = null) {
        if (method_exists($controller, $taskName)) {
            $controller->{$taskName}($params);
        } else {
            $controller->index();
        }
    }

}