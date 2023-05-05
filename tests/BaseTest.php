<?php

class BaseTest {
    private $class;
    private $publicMethods;

    public function __construct($class) {
        $this->class = $class;
        $this->publicMethods = $this->getPublicMethods();
    }

    private function getPublicMethods() {
        $methods = get_class_methods($this->class);
        $publicMethods = [];

        foreach ($methods as $method) {
            $reflectionMethod = new ReflectionMethod($this->class, $method);
            if ($reflectionMethod->isPublic()) {
                $publicMethods[] = $method;
            }
        }

        return $publicMethods;
    }

    public function showMethods() {
        echo 'Methods of ' . $this->class . ':<br>' . json_encode($this->publicMethods, JSON_PRETTY_PRINT);
    }
}