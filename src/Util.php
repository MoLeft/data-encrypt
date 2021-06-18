<?php
namespace MoLeft\DataCrypt;

class Util
{

    /**
     * 格式化私钥字符串
     *
     * @param string $priKey 待格式化私钥
     * @return string
     */
    public static function formatPriKey($priKey) {
        $priKey = str_replace("\n",'',$priKey);
        $fKey = "-----BEGIN PRIVATE KEY-----\n";
        $len = strlen($priKey);
        for($i = 0; $i < $len; ) {
            $fKey = $fKey . substr($priKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PRIVATE KEY-----";
        return $fKey;
    }

    /**
     * 格式化公钥字符串
     *
     * @param string $pubKey 待格式化公钥
     * @return string
     */
    public static function formatPubKey($pubKey) {
        $pubKey = str_replace("\n",'',$pubKey);
        $fKey = "-----BEGIN PUBLIC KEY-----\n";
        $len = strlen($pubKey);
        for($i = 0; $i < $len; ) {
            $fKey = $fKey . substr($pubKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PUBLIC KEY-----";
        return $fKey;
    }

    /**
     * 生成签名字符串
     *
     * @param array $params 参数数组
     * @return void
     */
    public static function getSignString($params,$filter = []){
        foreach($filter as $k){
            unset($params[$k]);
        }
        ksort($params);
        reset($params);
        $pairs = array();
        foreach ($params as $k => $v) {
            if(!empty($v)){
                $pairs[] = "$k=$v";
            }
        }
        return implode('&', $pairs);
    }

}