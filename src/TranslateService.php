<?php
// +----------------------------------------------------------------------
// | NicePHP [ NICE TO MEET YOU ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: GongZiYan <1250472056@qq.com>
// +----------------------------------------------------------------------


namespace GongZiYan\Translate;


use ErrorException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GongZiYan\Translate\Exceptions\TranslateException;

class TranslateService implements TranslateInterface
{
    private $driver;

    private $spare_driver;

    private $config;

    private $options = ['verify' => false];

    private $from;

    private $to;

    public $source = false;

    public function __construct($config = [])
    {
        $this->config = $config;
        if (!count($config)) {
            $this->config = include(__DIR__ . '/../config/translate.php');
        }

        $this->driver = $this->config['defaults']['driver'];
        $this->spare_driver = $this->config['defaults']['spare_driver'];
        $this->from = $this->config['defaults']['from'];
        $this->to = $this->config['defaults']['to'];
    }

    public function driver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function options($options = [])
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * 执行翻译
     * @param $string
     * @param bool $source 原数据，针对google
     * @return mixed
     * @throws ErrorException
     * @throws GuzzleException
     * @throws TranslateException
     */
    public function translate($string, $source = false)
    {
        $this->source = $source;
        try {
            return $this->sendTranslate($string, $this->driver);
        } catch (Exception $e) {
            //自动切换为备用渠道
            return $this->sendTranslate($string, $this->spare_driver);
        }
    }

    /**
     * 执行请求
     * @param $string
     * @param $driver
     * @return mixed
     * @throws TranslateException
     * @throws ErrorException
     * @throws GuzzleException
     */
    protected function sendTranslate($string, $driver)
    {
        $appKey = $this->config['drivers'][$driver]['app_key'];
        $appId = $this->config['drivers'][$driver]['app_id'];
        $baseUrl = $this->config['drivers'][$driver]['base_url'];
        switch ($driver) {
            case 'baidu':
                $obj = new Baidu($appId, $appKey, $this->from, $this->to, $baseUrl, $this->options);
                break;
            case 'youdao':
                $obj = new YouDao($appId, $appKey, $this->from, $this->to, $baseUrl, $this->options);
                break;
            case 'google':
                $obj = new Google($appId, $appKey, $this->from, $this->to, $baseUrl, $this->options);
                break;
            default:
                throw new TranslateException(10003);
        }

        return $obj->translate($string, $this->source);
    }

    /**
     * @param $attr
     * @param $value
     * @return $this
     */
    public function __set($attr, $value)
    {
        $this->{$attr} = $value;
        return $this;
    }

    /**
     * @param $attr
     * @return mixed
     */
    public function __get($attr)
    {
        return $this->{$attr};
    }
}
