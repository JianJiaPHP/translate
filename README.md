# Translate for PHP

---

## Installing

```shell
$ composer require GongZiYan/translate
```

### configuration 

```php
// config/translate.php

return [
    // 使用什么翻译驱动
    // 目前支持: "baidu", "youdao", "google"
    /**
     *  默认使用google  google使用的是免费接口爬取，目前能用，为了确保稳定，请配置一个备用服务， 目前只有google和baidu 支持繁体翻译
     **/

    'defaults' => [
        'driver' => 'google',   // 默认使用google翻译
        'spare_driver' => 'baidu',  // 备用翻译api ,第一个翻译失败情况下，调用备用翻译服务，填写备用翻译api 需要在下面对应的drivers中配置你参数
        'from' => 'zh',   // 原文本语言类型 ，目前支持：auto【自动检测】,en【英语】,zh【中文】,hk【繁体】，jp【日语】,ko【韩语】，fr【法语】，ru【俄文】，pt【西班牙】
        'to' => 'en',     // 翻译文本 ：en【英语】,zh【中文】,hk【繁体】，jp【日语】,ko【韩语】，fr【法语】，ru【俄文】，pt【西班牙】,
    ],

    'drivers' => [
        'baidu' => [
            'base_url' => 'https://api.fanyi.baidu.com/api/trans/vip/translate',
            'app_id' => '',
            'app_key' => '',
        ],

        'youdao' => [
            'base_url' => 'https://openapi.youdao.com/api',
            'app_id' => '',
            'app_key' => '',
        ],

        'google' => [
            'base_url' => 'http://translate.google.cn/translate_a/single',
            'app_id' => '',
            'app_key' => '',
        ],
    ],
];

```


## Usage


```php
use GongZiYan\Translate\TranslateService;

$config = include($youerpath.'/translate.php');

$obj = new TranslateService($config);
$result = $obj->translate('你知道我对你不仅仅是喜欢');
print_r($result);

```


Example:

```php
 // 动态更改翻译服务商
 $config = include($youerpath.'/translate.php');
 $obj = new TranslateService($config);
 $obj->driver('baidu')->translate('你知道我对你不仅仅是喜欢');
 print_r($result);
 //You know I'm not just like you
 
 // 动态更改语种
 
 $from = 'en';
 $to = 'zh';
 $result =  $obj->driver('baidu')->from($from)->to($to)->translate('I love you.');
print_r($result);

```

## License

MIT

