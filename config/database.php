<?php
class connectBD
{
    private $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    public static $DBH;

    public function __construct()
    {
        $ini = include('./config/config.php');
        if (self::$DBH != null)
            return $this->DBH;
        $this->DBH = new PDO($ini['host'], $ini['username'], $ini['password'], $this->opt);
    }


    public function connect()
    {
        if (self::$DBH != null)
            return $this->DBH;
        return new self;
    }

    public function closeConnect()
    {
        $this->DBH = null;
    }

}
