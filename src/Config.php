<?php
namespace MoLeft\DataCrypt;

class Config
{
    // 平台公钥
    private $public_key;
    // 用户私钥
    private $private_key;

    /**
     * 构造函数
     *
     * @param array $config_options 配置数组
     */
    public function __construct($config_options)
    {
        $this->checkParam($config_options);
        $this->public_key = new PublicKey();
        $this->private_key = new PrivateKey();
        switch($config_options['format_type']){
            case 'string':
                $this->public_key->setKeyAsString($config_options['public_key']);
                $this->private_key->setKeyAsString($config_options['private_key']);
                break;
            case 'file':
                $this->public_key->setKeyAsFile($config_options['public_key']);
                $this->private_key->setKeyAsFile($config_options['private_key']);
                break;
            default:
                throw new DataEncryptException($config_options['format_type'],10003);
                break;
        }
    }

    private function checkParam($config_options){
        $keys = ['format_type','private_key','public_key'];
        $keys_conf = null;
        // 检查参数列表是否为空
        foreach($config_options as $key => $value){
            $keys_conf[] = $key;
            if(in_array($key,$keys) && (!isset($value)||empty($value))){
                throw new DataEncryptException($key,10002);
            }
        }
        // 获取数组交集，验证参数是否完整
        $keys_inter = array_intersect($keys,$keys_conf);
        if($keys_inter != $keys){
            // 取keys_inter与keys的差集，打印出缺少的参数
            $keys_diff = array_diff($keys,$keys_inter);
            throw new DataEncryptException(implode(',',$keys_diff),10001);
        }
        // 礼貌性返回个true
        return true;
    }

    public function getPublicKey(){
        return $this->public_key;
    }

    public function getPrivateKey(){
        return $this->private_key;
    }
}