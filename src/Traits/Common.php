<?php
/**
 * 公用方法
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 11:23:09
 */

namespace Radish\WeChatPay\Traits;

trait Common
{

    /**
     * XML转换成数组
     * @param  xml $xml 
     * @return array
     */
    public function xmlToArray($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 输出xml字符
    **/
    public function arrayToXml(array $array, $time = true)
    {
        $xml = "<xml>";
        if (!isset($array['CreateTime']) && $time) {
            $array['CreateTime'] = time();
        }
        foreach ($array as $key => $val)
        {
            if (is_numeric($val)) {
                $xml .= "<".$key.">".$val."</".$key.">";
            } else if ($key == 'KfAccount') {
                $xml .= "<TransInfo><".$key."><![CDATA[".$val."]]></".$key."></TransInfo>";
            } else {
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .= "</xml>";

        return $xml;
    }

    /**
      * 获得随机字符串
      * @param $len             需要的长度
      * @param $special        是否需要特殊符号
      * @return string       返回随机字符串
      */
    public function getRandomStr($len = 20, $special = false)
    {
        $chars = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k","l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v","w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G","H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        if($special){
            $chars = array_merge($chars, ["!", "@", "#", "$", "?", "|", "{", "/", ":", ";", "%", "^", "&", "*", "(", ")", "-", "_", "[", "]", "}", "<", ">", "~", "+", "=", ",", "."]);
        }
        $charsLen = count($chars) - 1;
        shuffle($chars);                            //打乱数组顺序
        $str = '';
        for($i=0; $i<$len; $i++){
         $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
        }

        return $str;
    }

    /** 
    * 获取服务器端IP地址 
     * @return string 
     */  
    public function getServerIp()
    {   
        if (isset($_SERVER)) {
            if($_SERVER['SERVER_ADDR']) {
                $serverIp = $_SERVER['SERVER_ADDR'];
            } else {
                $serverIp = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $serverIp = getenv('SERVER_ADDR');
        }

        return $serverIp;
    }

    protected function getMessage($xml)
    {
        $array = $this->xmlToArray($xml);
        if ($array['return_code'] == 'FAIL') {
            throw new \Radish\WeChatPay\Exception\WeChatPayException("支付请求失败!", $xml);
        } else {
            return $array;            
        }
    }
}