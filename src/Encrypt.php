<?php

namespace MoLeft\DataCrypt;

use Exception;
use MoLeft\DataCrypt\Config;

class Encrypt
{
    private $config;
    private $json;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function encrypt($data){
        if(is_array($data)){
            throw new DataEncryptException('encrypt()',30001);
        }
        try{
            $this->json['data'] = $this->config->getPrivateKey()->encode($data);
        } catch (Exception $e){
            throw new DataEncryptException($e->getMessage(),30005);
        }
        return $this;
    }

    public function sign($data,$filter = []){
        if(!isset($this->json['data'])){
            throw new DataEncryptException('encrypt()',30006);
        }
        if(!is_array($data)){
            throw new DataEncryptException('sign()',30002);
        }
        try{
            $this->json['sign'] = $this->config->getPrivateKey()->sign(Util::getSignString($data));
        } catch (Exception $e){
            throw new DataEncryptException($e->getMessage(),102);
        }
        return $this;
    }

    public function json(){
        if(!isset($this->json['data'])){
            throw new DataEncryptException('encrypt()',30005);
        }
        return json_encode($this->json);
    }
}
