<?php
/**
 * 企业付款
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 10:55:00
 */

namespace Radish\WeChatPay\Traits;

use Radish\Network\Curl;

trait EnterprisePay
{
    /**
     * 发送现金红包
     * @param  array  $options 请求参数
     * @return mixed         响应结果
     */
    public function sendRedPackent(array $options)
    {
        $params = [
            'nonce_str' => $this->getRandomStr(),
            'mch_billno' => $options['mch_billno'],
            'mch_id' => self::$mchId,
            'wxappid' => self::$appId,
            'send_name' => $options['send_name'],
            're_openid' => $options['open_id'],
            'total_amount' => $options['total_amount'],
            'total_num' => isset($options['total_num']) ? $options['total_num'] : 1,
            'wishing' => $options['wishing'] ?: '恭喜发财',
            'client_ip' => self::$serverIp,
            'act_name' => $options['act_name'] ?: '现金红包推广',
            'remark' => $options['remark'] ?: '备注信息',
            'scene_id' => $this->getSceneId($options['scene_id']),
            // 'risk_info'
        ];

        return $this->sendResult($params, 'send_red_pack');
    }

    /**
     * 场景id
     * 发放红包使用场景，红包金额大于200或者小于1元时必传
     * @param  string $val 场景类型
     * @return string      场景类型
     */
    protected function getSceneId($val)
    {
        $sceneIds = [
            'PRODUCT_1', //商品促销
            'PRODUCT_2', //抽奖
            'PRODUCT_3', //虚拟物品兑奖 
            'PRODUCT_4', //企业内部福利
            'PRODUCT_5', //渠道分润
            'PRODUCT_6', //保险回馈
            'PRODUCT_7', //彩票派奖
            'PRODUCT_8', //税务刮奖
        ];
        if (in_array($val, $sceneIds)) {
            return $val;
        } else {
            return $sceneIds[1];
        }
    }

    /**
     * 企业付款到微信用户零钱
     * @param  array  $options 请求参数
     * @return mixed           响应结果
     */
    public function payForChange(array $options)
    {
        $params = [
            'mch_appid' => self::$appId,
            'mchid' => self::$mchId,
            // 'device_info' => '', //设备号
            'nonce_str' => $this->getRandomStr(),
            'partner_trade_no' => $options['trade_no'],
            'openid' => $options['open_id'],
            'check_name' => $this->getOption($options, 'check_name', 'NO_CHECK'), //校验用户姓名选项 NO_CHECK ： 不校验真实姓名 FORCE_CHECK ： 强校验真实姓名
            're_user_name' => $this->getOption($options, 'user_name'),
            'amount' => $options['amount'] * 100,
            'desc' => $this->getOption($options, 'desc', '企业付款到微信用户零钱'),
            'spbill_create_ip' => self::$serverIp,
        ];

        return $this->sendResult($params, 'pay_for_change');
    }

    /**
     * 选填参数预处理
     * @param  array  $options 请求数据
     * @param  string $key     需要获取请求数据的KEY
     * @param  string $default 默认值
     * @return mixed           处理过得值
     */
    private function getOption(array $options, string $key, $default = '')
    {
        $value = $default;
        isset($options[$key]) && $value = $options[$key];

        return $value;
    }

    /**
     * 公共的请求接口的方法
     * @param  array  $params 请求参数
     * @param  string $urlKey 请求地址
     * @return mixed          响应结果
     */
    protected function sendResult(array $params, string $urlKey)
    {
        $params['sign'] = strtoupper($this->sign($params));
        $option = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSLCERTTYPE => 'PEM',
            CURLOPT_SSLCERT => self::$apiclientCert,
            CURLOPT_SSLKEYTYPE => 'PEM',
            CURLOPT_SSLKEY => self::$apiclientKey,
        ];
        $params = $this->arrayToXml($params, false);
        $result = Curl::post($this->getApiUrl($urlKey), $params, $option);

        return $this->getMessage($result);
    }

    /**
     * 生成签名验签
     * @param  Array $params    请求参数
     * @param  string $connector 拼接符
     * @param  string $type      加密方式
     * @return string            加密后字段
     */
    public function sign($params, $connector = '&', $type = 'md5')
    {
        $sign = $this->jointString($params, $connector) . $connector . 'key=' . self::$key;
        if ($type == 'md5') {
            $sign = md5($sign);
        }

        return $sign;
    }

    /**
     * 获取请求连接
     * @param  string $key 连接类型
     * @return string      请求地址
     */
    protected function getApiUrl($key)
    {
        $urlMap = [
            //POST 红包接口 1800次/分钟
            'send_red_pack' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack',
            //POST 企业付款到用户微信零钱  1800次/分钟
            'pay_for_change' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers'
        ];

        return $urlMap[$key];
    }
}