<?php
include './vendor/autoload.php';

use MoLeft\DataCrypt\Config;
use MoLeft\DataCrypt\Encrypt;
use MoLeft\DataCrypt\DataEncryptException;
use MoLeft\DataCrypt\Decrypt;

/**
 * 配置参数
 */
$config_option = [
    'format_type' => 'file',                                    // 公钥私钥读取格式 file:从文本读取 string:从变量中读取
    'private_key' => dirname(__FILE__).'/test_key/private_key', // 设置私钥路径(建议绝对路径)
    'public_key'  => dirname(__FILE__).'/test_key/public_key',  // 设置公钥路径(建议绝对路径)
];

// 创建一个Config实例 
// 防止设置配置参数不规范,可以使用try..catch来捕获异常
$config =  new Config($config_option);

// try{
//     $config =  new Config($config_option);
// }catch(DataEncryptException $e){
//     die($e->getMessage());
// }

// 这是要返回的消息，目前仅支持文本和json
$data = json_encode(['ret' => '1','msg' => '登陆成功']);
echo "\n\n原始数据：\n{$data}\n";

// 这是get参数，可以用于签名校验
$parm = [
    'act' => 'login',
    'user' => 'root',
    'pass' => 'root'
];

/*******************************************************************/
/*                               加密实例                          */
/*******************************************************************/

// 实例化一个加密对象
$encrypt = new Encrypt($config);
// 通过encrypt()方法将$data加密 再通过sign()方法可以生成一个签名 json()方法返回加密好的数据
$encrypt_data = $encrypt->encrypt($data)->sign($parm)->json();
echo "\n\n加密数据：\n{$encrypt_data}\n";


/*******************************************************************/
/*                               解密实例                          */
/*******************************************************************/

// 实例化一个解密对象
$decrypt = new Decrypt($config);
// 通过data()方法设置要解密的数据 再通过verify()方法验证签名(如果没有生成签名可以忽略) decrypt返回解密的数据
$decrypt_data = $decrypt->data($encrypt_data)->verify($parm)->decrypt();
echo "\n\n解密数据：\n{$decrypt_data}\n";