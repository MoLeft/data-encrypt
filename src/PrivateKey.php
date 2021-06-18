<?php
namespace MoLeft\DataCrypt;

class PrivateKey
{

    private $private_key;

    public function setKeyAsString($private_key){
        $this->private_key = Util::formatPriKey($private_key);
    }

    public function setKeyAsFile($private_key){
        if(!is_file($private_key)){
            throw new DataEncryptException($private_key,20001);
        }
        $this->private_key = file_get_contents($private_key);
    }

    public function sign($signString){
        $priKey = $this->private_key;
        $privKeyId = openssl_pkey_get_private($priKey);
        $signature = '';
        openssl_sign($signString, $signature, $privKeyId);
        openssl_free_key($privKeyId);
        return base64_encode($signature);
    }

    public function encode($data){
        $priKey = $this->private_key;
        $privKeyId = openssl_pkey_get_private($priKey);
        openssl_private_encrypt($data,$encode_data,$privKeyId);
        return base64_encode($encode_data);
    }

    public function decode($data){
        $priKey = $this->private_key;
        $privKeyId = openssl_pkey_get_private($priKey);
        openssl_private_decrypt(base64_decode($data),$decode_data,$privKeyId);
        return $decode_data;
    }

    public function get(){
        return $this->private_key;
    }
}