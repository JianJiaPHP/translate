<?php
// +----------------------------------------------------------------------
// | NicePHP [ NICE TO MEET YOU ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: GongZiYan <1250472056@qq.com>
// +----------------------------------------------------------------------


namespace GongZiYan\Translate\Traits;


use GongZiYan\Translate\Exceptions\TranslateException;

trait ServiceTrait
{
    private $app_id;

    private $app_key;

    private $base_url;

    private $options;

    private $httpClient;

    private $from;

    private $to;

    public $source = false;

    public function __construct($app_id, $app_key, $from, $to, $base_url, $options = [])
    {
        $this->app_id = $app_id;
        $this->app_key = $app_key;
        $this->from = $this->checkLanguage($from);
        $this->to = $this->checkLanguage($to);
        $this->base_url = $base_url;
        $this->options = $options;
    }

    /**
     * @param $language
     * @return mixed
     * @throws TranslateException
     */
    private static function checkLanguage($language)
    {
        if (!isset(self::$language[$language])) {
            throw new TranslateException('10000');
        }

        return self::$language[$language];
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