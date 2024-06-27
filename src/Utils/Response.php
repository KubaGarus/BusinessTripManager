<?php
namespace Utils;

class Response
{
    public $data;
    public $message;
    public $cssClass;
    public $error;

    public function __construct()
    {
        $this->data = [];
        $this->message = "";
        $this->cssClass = "";
        $this->error = false;
    }
}
?>
