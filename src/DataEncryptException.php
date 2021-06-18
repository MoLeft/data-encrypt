<?php
namespace MoLeft\DataCrypt;

use \Exception;

class DataEncryptException extends Exception
{
    protected static $error_message;
    public function __construct($message,$code = 0, Exception $previous = null)
    {
        self::$error_message = include dirname(__FILE__).'/error_code.php';
        parent::__construct(self::replace($code,$message), $code, $previous);
    }

    private static function replace($code,$str){
        return str_replace('{$$}',$str,self::$error_message[$code]);
    }

    public function __toString()
    {
        return '异常： '.parent::getFile().' 的第 '.parent::getLine().' 行，['.$this->code.']: '.$this->message."\n";
    }
}
