<?php
/**
 * JS SDK
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 18:55:55
 */
namespace Radish\WeChat\Traits;

use Radish\Network\Curl;
use Radish\WeChat\Exception\WeChatPayException;

trait JsSdkConfig
{
    protected $jsapiTickent = '';

    /**
     * 获取access_token
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
     * 生成signature
     * @param  array  $params 参数
     * @return string         加密后的数据
     */
    public function signature(array $params)
    {
        $string = $this->jointString($params);
        
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
}
