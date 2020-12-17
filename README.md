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

// 配置
$config = [
    'userID'    => 'xxxxxx',
    'userPWD'   => 'xxxxxx',
    'accountNo' => 'xxxxxxxxxxxxxxxxxx',
    'sign_host' => '127.0.0.1:8010',
    'ssl_host'  => '127.0.0.1:7071',
];


$lib = new \Bank\Actions\Main($config);

// 获取sessionId
$lib->getSessionId();

// 转账
$lib->transferCrossBank();

// 查询转账结果
$lib->queryTransferResult();

// 响应体
$lib->response;


```


## TODO
a lot


## License

MIT
