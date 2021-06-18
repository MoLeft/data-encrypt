# Data-Encrypt
一个简简单单的信息加密库，利用私钥将数据加密，将公钥上传至服务端，实现相对安全的数据传输

作者：MoLeft

博客：[www.moleft.cn](https://www.moleft.cn)

# 使用方法

## 声明一个配置数组

```php
$config_option = [
    'format_type' => 'file',
    'private_key' => dirname(__FILE__).'/test_key/private_key',
    'public_key'  => dirname(__FILE__).'/test_key/public_key',
];
```
``format_type`` : 可选 ``string`` 和 ``file``

string: 私钥``private_key``和公钥``public_key``需传入字符串形式

file:需要传入文件地址，建议``绝对路径``

## 创建一个Config实例

```php
$config =  new Config($config_option);
```
也可以捕获异常
```php
use MoLeft\DataCrypt\DataEncryptException;


try{
    $config =  new Config($config_option);
}catch(DataEncryptException $e){
    die($e->getMessage());
}
```

## 随便写点数据测试

```php
// 假设这是返回数据
$data = json_encode(['ret' => '1','msg' => '登陆成功']);
// 假设这是参数
$parm = [
    'act' => 'login',
    'user' => 'root',
    'pass' => 'root'
];
```

## 加密

```php
// 实例化一个加密对象
$crypt = new Crypt($config);
// 加密，签名，返回加密好的数据
$crypt_data = $crypt->crypt($data)->sign($parm)->json();
```

## 解密

```php
// 实例化一个解密对象
$decrypt = new Decrypt($config);
// 设置解密数据 验签 返回解密数据
$decrypt_data = $decrypt->data($crypt_data)->verify($parm)->decrypt();
```

## 方法说明

1. ``sign()``和``verify()``是签名和验签的方法不是必须的，如果你喜欢也可以签名了不验签，有签名我不验，就是玩~

2. 3. ``Crypt`` 对象最后必须调用``json()``方法来获取加密好的数据，因为我只写了这一种。

``Decrypt`` 对象一定一定要使用``data()``方法设置需要解密的数据，别问为什么，我开心。

## 最后再给大家看看测试的数据吧

```php
原始数据：
{"ret":"1","msg":"\u767b\u9646\u6210\u529f"}


加密数据：
{"data":"Suj8S9srh50P74FIO4s9dHDWvYSB+TxNhrg68OP4mqTeQ9UezO2CZPQ\/Mqzk9r0TUJ\/VReDqxtwrP4O8K6GlmpgyW\/RTXMPy5rGsv1K\/RD45LMPVr1VJyiyKDqjNLZ+D8SLFwNi5dn1uukGqeTO6IzcD\/vf4WuYQ7xq\/yV0hF1e+gh4GkYSQczFWPJNtwWHRDi8Q0rOu48tPbb\/5poduKyObh7CZDiQ3OWuXyS7b2KMMGAL9BM3p0Juzq19QmfSYFl3uBzNu+\/N7LuHhy3egMYNCHpYPkr7xcT9Ra7muaMuTMcJPKSv\/APark3cJnl5ZA+HzJXrDEFJjNIgRAeaBXQ==","sign":"JClGoEWdwIAfUSnIPv1eaqzH7ETC6eeq0c3smEeLq4FkOtEQB\/Q3qLww76ZqWy1Dc4ubyB7eTjGiKouJdusRq0z4glBz0Vze1ZRlQMYVydmUiL4KlCE25fHwyfDTE2KTQs4ZG1bSfe1ENz\/twaLlvBJwhATsoiq7oV1YUAWeAinpREkkdSQL+N1KzkDuIp9JP73b0r3n5g2Rx1QINS1\/T6VIsA2wkqNCHm+43y2A928CCkQdve7VFk\/rL71+XdKWzYRoU+Xde1NPEDOuWP7SzV\/3+DdBwmUjahS\/pIBq0twueQYstPSSd7GU2Lmh\/92+y1SKamW2lIx1kmHcwgcZmA=="}


解密数据：
{"ret":"1","msg":"\u767b\u9646\u6210\u529f"}
```