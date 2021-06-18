<?php
namespace MoLeft\DataCrypt;

use Exception;
use MoLeft\DataCrypt\Config;

class Decrypt
{
    private $config;
    private $json;
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function data($data){
        $this->json = json_decode($data,true);
        return $this;
    }

    public function decrypt(){
        if(!$this->json){
            throw new DataEncryptException('decrypt()',30003);
        }
        try{
            $this->json['data'] = $this->config->getPublicKey()->decode($this->json['data']);
        } catch (Exception $e){
            throw new DataEncryptException($e->getMessage(),'102');
        }
        return $this->json['data'];
    }

    public function verify($toSign){
        if(!$this->json){
            throw new DataEncryptException('verify()',30003);
        }
        if(isset($this->json['sign'])){
            try{
                $result = $this->config->getPublicKey()->verify(Util::getSignString($toSign),$this->json['sign']);
            } catch (Exception $e){
                throw new DataEncryptException($e->getMessage(),'102');
            }
            if(!$result) throw new DataEncryptException('',101);
        }else{
            throw new DataEncryptException('',30004);
        }
        return $this;
    }
}