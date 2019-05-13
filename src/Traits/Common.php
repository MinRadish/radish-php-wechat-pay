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
      * @param $len          需要的长度
      * @param $special      是否需要特殊符号
      * @return string       返回随机字符串
      */
    public function getRandomStr($len = 20, $special = false)
    {
        $chars = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k","l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v","w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G","H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        if($special){
            $chars = array_merge($chars, ["!", "@", "#", "$", "?", "|", "{", "/", ":", ";", "%", "^", "&", "*", "(", ")", "-", "_", "[", "]", "}", "<", ">", "~", "+", "=", ",", "."]);
        }
        $charsLen = count($chars) - 1;
        shuffle($chars);  //打乱数组顺序
        $str = '';
        for($i=0; $i<$len; $i++){
            $str .= $chars[mt_rand(0, $charsLen)]; //随机取出一位
        }

        return $str;
    }

    /**
     * 请求响应错误信息
     * @param  xml $xml 响应数据
     * @param  String $fun 获取对应接口返回错误码信息
     * @return mixed    响应结果
     */
    protected function getMessage($xml, $fun = '')
    {
        $array = $this->xmlToArray($xml);
        if ($array['return_code'] == 'FAIL') {
            $msg = '支付请求失败!';
            if ($fun && method_exists($this, $fun)) {
                $temp = $this->$fun($array['err_code']);
                $temp && $msg = $temp;
            } else {
                isset($array['err_code_des']) && $msg = $array['err_code_des'];
            }
            throw new \Radish\WeChatPay\Exception\WeChatPayException($msg, $xml);
        } else {
            return $array;            
        }
    }

    /**
     * 拼接数组
     * @param  array  $params    待拼接
     * @param  string $connector 拼接符
     * @return string            拼接后字符串
     */
    public function jointString(array $params, $connector = '&')
    {
        ksort($params);
        $d = $string = '';
        foreach ($params as $key => $val) {
            $val && $string .= $d . $key . '=' . $val;
            $d = $connector;
        }

        return $string;
    }

    /**
     * 获取错误代码
     * @param  string $key 代码
     * @return String 错误代码与信息
     */
    protected function getCodeMap($key)
    {
        $codeMap = [
            //获取access_token
            '-1' => '系统繁忙，此时请开发者稍候再试',
            '40001' => 'AppSecret错误或者AppSecret不属于这个公众号，请开发者确认AppSecret的正确性',
            '40002' => '请确保grant_type字段值为client_credential',
            '40164' => '调用接口的IP地址不在白名单中，请在接口IP白名单中进行设置。（小程序及小游戏调用不要求IP地址在白名单内。）',
        ];
        $info = isset($codeMap[$key]) ? $codeMap[$key] : false;

        return $info;
    }

    /**
     * 生成订单号
     * @param  string $joint 后缀
     * @return string        返回订单号
     */
    public function orderNo($joint = null)
    {
        if (!$joint) {
            $joint = $this->getRandomStr(5);
        }
        $orderNo = date("YmdHis") . $joint;

        return $orderNo;
    }
}