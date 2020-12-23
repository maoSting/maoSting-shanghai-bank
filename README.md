# 上海银行银企直联系统接口


## Introduction
上海银行银企直联系统接口


## Requirement
1. PHP >= 7.0
2. **[Composer](https://getcomposer.org/)**
3. php-curl-class/php-curl-class
4. extension mb_string



## Usage
```
// 编码为utf-8，会自动转化为gbk

// 配置
$config = [
    'userID'    => 'xxxxxx',
    'userPWD'   => 'xxxxxx',
    'accountNo' => 'xxxxxxxxxxxxxxxxxx',
    'sign_host' => '127.0.0.1:8010',
    'ssl_host'  => '127.0.0.1:7071',
];


$lib = new \Bank\Actions\Main($config);

// 获取银行列表
$lib->getBanks();

// 获取sessionId
$lib->getSessionId();

// 转账
$actionLib->setSerialNo($xxx); // 设置交易序列
$lib->transferCrossBank($xxxxxxxx...);

// 查询转账结果
$actionLib->setSerialNo(null);
$lib->queryTransferResult($xxx);

// 响应体
$lib->response;


```


## TODO
a lot


## License

MIT
