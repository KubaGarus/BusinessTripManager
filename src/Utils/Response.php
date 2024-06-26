<?php

class Response
{
    public array $data;
    public string $message, $cssClass;
    public bool $error;

    public function __construct()
    {
        $this->data = [];
        $this->message = "";
        $this->cssClass = "";
        $this->error = false;
    }
}