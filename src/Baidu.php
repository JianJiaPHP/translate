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

class Baidu implements TranslateInterface
{
    use ServiceTrait;

    protected static $language = [
        ''           => 'auto',   //中文
        'auto'       => 'auto',   //中文
        'zh'         => 'zh',   //中文
        'zh-cn'      => 'zh',   //中文
        'zh-Hans'    => 'zh',   //中文
        'en'         => 'en',   //英语
        'cht'        => 'cht',   //繁体
        'hk'         => 'cht',   //繁体
        'zh-hk'      => 'cht',   //繁体
        'zh-tw'      => 'cht',   //繁体
        'zh-Hant-HK' => 'cht',   //繁体
        'zh-Hant'    => 'cht',   //中文
        'jp'         => 'jp',   //日文
        'ja'         => 'jp',   //日文
        'ko'         => 'kor',  //韩文
        'kor'        => 'kor',  //韩文
        'fr'         => 'fra',  //法语
        'fra'        => 'fra',  //法语
        'ru'         => 'ru',   //俄语
        'es'         => 'spa',  //西班牙语
        'pt'         => 'pt',  //葡萄牙语
        'es-419'     => 'spa',  //西班牙(拉丁美)
        'spa'        => 'spa',  //西班牙(拉丁美)
        'ms'         => 'may',  //西班牙(拉丁美)
        'may'        => 'may',  //西班牙(拉丁美)
        'vi'         => 'vie',  //越南
        'vie'        => 'vie',  //西班牙(拉丁美)
        'th'         => 'th',  //西班牙(拉丁美)
        'pt-PT'      => 'pt',  //葡萄牙语
        'pt-BR'      => 'pt',  //葡萄牙语
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
     * @param $string
     * @return array
     */
    private function getData($string)
    {
        $salt = time();
        return [
            "from"  => $this->from,
            "to"    => $this->to,
            "appid" => $this->app_id,
            "q"     => $string,
            "salt"  => $salt,
            "sign"  => $this->getSign($string, $salt),
        ];
    }

    /**
     * @param $string
     * @param $time
     * @return string
     */
    private function getSign($string, $time)
    {
        $str = $this->app_id . $string . $time . $this->app_key;
        return md5($str);
    }

    /**
     * @param $result
     * @return mixed
     * @throws TranslateException
     */
    private function response($result)
    {
        if (is_array($result) && isset($result['error_code'])) {
            throw new TranslateException($result['error_code']);
        }

        if (is_array($result) && isset($result['trans_result'])) {
            if ($this->source) {
                return $result;
            }
            return $result['trans_result'][0]['dst'];
        }

        throw new TranslateException(10003);
    }
}