<?php

class BaseClass {
    private $successfully;
    private $errorMessages = [];

    private function setSuccessfully($successfully) {
        $this->successfully = $successfully;
    }

    public function getSuccessfully() {
        return $this->successfully;
    }

    private function addErrorMessage($errorMessage) {
        $this->errorMessages[] = $errorMessage;
    }

    public function getErrorMessages() {
        return json_encode($this->errorMessages, JSON_PRETTY_PRINT);
    }
}