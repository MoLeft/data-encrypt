<?php

namespace MoLeft\DataCrypt;

class PublicKey
{

    private $public_key;

    public function setKeyAsString($public_key)
    {
        $this->public_key = Util::formatPubKey($public_key);
    }

    public function setKeyAsFile($public_key)
    {
        if (!is_file($public_key)) {
            throw new DataEncryptException($public_key, 20001);
        }
        $this->public_key = file_get_contents($public_key);
    }

    public function verify($toSign,$sign)
    {
        $signature_alg=OPENSSL_ALGO_SHA1;
        $publicKeyId = openssl_pkey_get_public($this->public_key);
        $result = openssl_verify($toSign, base64_decode($sign), $publicKeyId, $signature_alg);
        openssl_free_key($publicKeyId);
        return $result === 1 ? true : false;
    }

    public function encode($data){
        $pubKey = $this->public_key;
        $pubKeyId = openssl_pkey_get_public($pubKey);
        openssl_public_encrypt($data,$encode_data,$pubKeyId);
        return base64_encode($encode_data);
    }

    public function decode($data){
        $pubKey = $this->public_key;
        $pubKeyId = openssl_pkey_get_public($pubKey);
        openssl_public_decrypt(base64_decode($data),$decode_data,$pubKeyId);
        return $decode_data;
    }

    public function get()
    {
        return $this->public_key;
    }
}
