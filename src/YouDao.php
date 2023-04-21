<?php
// +----------------------------------------------------------------------
// | NicePHP [ NICE TO MEET YOU ]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: GongZiYan <1250472056@qq.com>
// +----------------------------------------------------------------------


namespace GongZiYan\Translate;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GongZiYan\Translate\Exceptions\TranslateException;
use GongZiYan\Translate\Traits\ServiceTrait;

class YouDao implements TranslateInterface
{
    use ServiceTrait;

    protected static $language = [
        '' => 'auto',   //中文
        'auto' => 'auto',   //中文
        'zh' => 'zh-CHS',   //中文
        'hk' => 'zh-CHT',   //繁体
        'en' => 'en',   //英文
        'jp' => 'ja',   //日文
        'ko' => 'ko',  //韩文
        'fr' => 'fr',  //法语
        'ru' => 'ru',   //俄语
        'es' => 'es',  //西班牙语
        'pt' => 'pt',  //葡萄牙语
    ];

    /**
     * @param $string
     * @param bool $source 返回原数据结构
     * @return mixed
     * @throws TranslateException
     * @throws GuzzleException
     */
    public function translate($string, $source = false)
    {
        $this->source = $source;
        $this->httpClient = new Client($this->options); // Create HTTP client
        $data = $this->getData($string);
        $response = $this->httpClient->request('POST', $this->base_url, [
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $this->response($result);
    }

    /**
     * @param $result
     * @return mixed
     * @throws TranslateException
     */
    private function response($result)
    {
        if (is_array($result) && $result['errorCode'] != 0) {
            throw new TranslateException($result['errorCode']);
        }

        if (is_array($result) && isset($result['translation'])) {
            if ($this->source) {
                return $result;
            }
            return $result['translation'][0];
        }

        throw new TranslateException(10003);
    }

    /**
     * @param $string
     * @return array
     */
    private function getData($string)
    {
        $salt = time();
        return [
            "from" => $this->from,
            "to" => $this->to,
            "appKey" => $this->app_id,
            "q" => $string,
            "salt" => $salt,
            "sign" => $this->getSign($string, $salt),
            "signType" => "v3",
            "curtime" => $salt
        ];
    }

    /**
     * @param $string
     * @param $time
     * @return string
     */
    private function getSign($string, $time)
    {
        $str = $this->app_id . $this->handleString($string) . $time . $time . $this->app_key;
        return hash('sha256', $str);
    }

    private function handleString($string)
    {
        $length = mb_strlen($string);
        if ($length > 20) {
            return mb_substr($string, 0, 10) . $length . mb_substr($string, -10);
        }

        return $string;
    }
}