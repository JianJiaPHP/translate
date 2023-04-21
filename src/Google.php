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
use GongZiYan\Translate\Traits\ServiceTrait;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Google implements TranslateInterface
{
    use ServiceTrait;

    protected static $language = [
        '' => 'auto',   //中文
        'auto' => 'auto',   //中文
        'zh' => 'zh-CN',   //中文
        'hk' => 'zh-TW',   //繁体
        'en' => 'EN',   //英文
        'jp' => 'jp',   //日文
        'ko' => 'kor',  //韩文
        'fr' => 'fra',  //法语
        'ru' => 'ru',   //俄语
        'es' => 'spa',  //西班牙语
        'pt' => 'pt',  //葡萄牙语
    ];

    /**
     * @param $string
     * @param bool $source 返回原数据结构
     * @return mixed
     * @throws ErrorException
     */
    public function translate($string, $source = false)
    {
        $driver = new GoogleTranslate($this->to, $this->from, $this->options);
        $driver->setUrl($this->base_url);

        if ($source) {
            $result = $driver->getResponse($string);
        } else {
            $result = $driver->translate($string);
        }

        return $result;
    }
}