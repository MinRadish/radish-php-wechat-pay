<?php
namespace Radish\WeChat\Traits;

use Radish\Network\Curl;
use Radish\WeChatPay\Exception\WeChatException;

/**
* @author Radish 1004622952@qq.com 2019-03-15
* 微信公众号accessToken的缓存（根据不同的框架去修改）
*/

trait AccessToken
{
    // https请求方式: GET
    private $tokenApiUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
    public $accessToken = '';

    use CustomCache;

    /**
     * 拼接URL
     * @return string URL
     */
    protected function getTokenApiUrl()
    {
        return $this->tokenApiUrl . '&appid=' . self::$AppID . '&secret=' . self::$AppSecret;
    }

    /**
     * 获取access_token
     * @return string 
     */
    public function getAccessToken()
    {
        if (!$this->accessToken) {
            $this->accessToken = $this->cacheGet('access_token');
            if (!$this->accessToken) {
                $array = $this->requestToken();
                $this->accessToken = $array['access_token'];
                $this->cacheSet('access_token', $this->accessToken);
            }
        }

        return $this->accessToken;
    }

    /**
     * 调用API请求access_token
     * @return Array 转换json后的数组
     */
    protected function requestToken()
    {
        $json = Curl::get($this->getTokenApiUrl());
        $array = json_decode($json, true);
        if (!isset($array['access_token'])) {
            $mes = $this->getCodeMap($array['errcode']) ?: '获取access_token失败请重试!';
            throw new WeChatException($mes, $json);
        }
        
        return $array;
    }
}