<?php

class View
{

    private $template;
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            throw new InvalidArgumentException("$name is missing...");
        }
    }

    public function setTemplate($tplName)
    {
        $this->template = $tplName;
    }

    public function replaceStyleWithCode($text){
        if($text == "hochglanz"){
            return "G";
        }
        if($text == "matt"){
            return "M";
        }
        if($text == "student"){
            return "R";
        }
    }

    public function parse()
    {
        $output = '';

        $file = "templates/" . $this->template . ".php";
        if (file_exists($file)) {
            ob_start();
            include $file;
            $output = ob_get_contents();
            ob_end_clean();
        } else {
            echo "templates/" . $this->template . ".php nicht gefunden";
        }
        return $output;
    }

    function formatDate($date = '2014-11-05 16:34:24', $switch = 'a'){
        $dateExplode = explode(' ', $date);
        $justDate = $dateExplode[0];
        $explodeJustDate = explode('-', $justDate);
        $justTime = $dateExplode[1];
        $formattedDate = array();
        $formattedDate['date'] = $explodeJustDate[2] . '.' . $explodeJustDate[1] . '.' . $explodeJustDate[0];
        $formattedDate['time'] = $justTime;
        if($switch == 't'){
            return $formattedDate['time'];
        }else if($switch == 'd'){
            return $formattedDate['date'];
        }else if($switch == 'a'){
            return $formattedDate['date'] . ' - ' . $formattedDate['time'];
        }
    }
} 