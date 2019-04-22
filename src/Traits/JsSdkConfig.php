<?php
/**
 * JS SDK
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 18:55:55
 */
namespace Radish\WeChatPay\Traits;

use Radish\Network\Curl;
use Radish\WeChat\Exception\WeChatPayException;

trait JsSdkConfig
{
    /**
     * 微信jsSdk调用凭证
     * @var string
     */
    protected $jsapiTickent = '';
    /**
     * 微信卡券jsApi凭证
     * @var string
     */
    protected $apiTickent = '';

    /**
     * 获取 jsapiTickent
     * @return string 
     */
    public function getJsapiTickent()
    {
        if (!$this->jsapiTickent) {
            $this->jsapiTickent = $this->cacheGet('jsapi_ticket');
            if (!$this->jsapiTickent) {
                $array = $this->requestJsapiTicket();
                $this->jsapiTickent = $array['ticket'];
                $this->cacheSet('jsapi_ticket', $this->jsapiTickent);
            }
        }

        return $this->jsapiTickent;
    }

    /**
     * 获取 apiTickent
     * @return string 
     */
    public function getApiTickent()
    {
        if (!$this->apiTickent) {
            $this->apiTickent = $this->cacheGet('api_ticket');
            if (!$this->apiTickent) {
                $array = $this->requestApiTicket();
                $this->apiTickent = $array['ticket'];
                $this->cacheSet('api_ticket', $this->apiTickent);
            }
        }

        return $this->apiTickent;
    }

    /**
     * 生成signature
     * @param  array  $params 参数
     * @return string         加密后的数据
     */
    public function signature(array $params)
    {
        $params['jsapi_ticket'] = $this->getJsapiTickent();
        $string = $this->jointString($params);
        
        return sha1($string);
    }

    /**
     * 生成 api_card sign
     * @param  array  $params 参数
     * @return string         加密后的数据
     */
    public function cardSign(array $params)
    {
        $params['api_ticket'] = $this->getApiTickent();
        sort($params, SORT_STRING);
        $string = implode($params);

        return sha1($string);
    }

    /**
     * 调用API请求 jsapi_ticket
     * @return Array 转换json后的数组
     */
    protected function requestJsapiTicket()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->getAccessToken() . '&type=jsapi';
        $json = Curl::get($url);
        $array = json_decode($json, true);
        if (!isset($array['ticket'])) {
            $mes = $array['errmsg'] ?: '获取jsapi_ticket失败请重试!';
            throw new WeChatPayException($mes, $json);
        }
        
        return $array;
    }

    /**
     * 调用API请求 api_ticket
     * @return Array 转换json后的数组
     */
    protected function requestApiTicket()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $this->getAccessToken() . '&type=wx_card';
        $json = Curl::get($url);
        $array = json_decode($json, true);
        if (!isset($array['ticket'])) {
            $mes = $array['errmsg'] ?: '获取api_ticket失败请重试!';
            throw new WeChatPayException($mes, $json);
        }
        
        return $array;
    }
}
