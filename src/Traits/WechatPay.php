<?php
/**
 * 微信支付
 * @author Radish (1004622952@qq.com)
 * @date    2019-08-26 18:37:02
 */

namespace Radish\WeChatPay\Traits;

trait WechatPay
{
    /**
     * 微信支付同一下单
     * @param  array  $options 参数
     * @return         接口响应结果
     */
    public function orderUnify(array $options)
    {
        $params = [
            'appid' => self::$appId,
            'mch_id' => self::$mchId,
            // 'device_info',
            'nonce_str' => $this->getRandomStr(),
            'spbill_create_ip' => self::$serverIp,
        ];
        $params = array_merge($params, $options);

        return $this->sendResult($params, 'order_unify');
    }

    /**
     * 微信支付退款
     * @param  array  $options 参数
     * @return         [description]
     */
    public function orderRefund(array $options)
    {
        $params = [
            'appid' => self::$appId,
            'mch_id' => self::$mchId,
            // 'device_info',
            'nonce_str' => $this->getRandomStr(),
            // 'spbill_create_ip' => self::$serverIp,
        ];
        $params = array_merge($params, $options);

        return $this->sendResult($params, 'order_refund', true);
    }

    /**
     * 回调验证
     * @param  mixed $xml 微信回调参数
     * @return mixed      返回验证结果
     */
    public function notify($xml)
    {
        if (!is_array($xml)) {
            $params = $this->xmlToArray($xml);
        } else {
            $params = $xml;
        }
        if (!is_array($params)) {
            throw new \Radish\WeChatPay\Exception\WeChatPayException('无效的数据参数', $xml);
        }
        ksort($params);
        if ($params['return_code'] == 'SUCCESS' && $params['result_code'] == 'SUCCESS') {
            $sign = strtoupper($params['sign']);
            unset($params['sign']);
            $newSign = strtoupper($this->sign($params));
            if ($sign === $newSign) {
                return $params;
            } else {
                return false;
            }
        } else {
            $msg = '未完成对应操作';
            isset($params['return_msg']) && $msg = $params['return_msg'];
            throw new \Radish\WeChatPay\Exception\WeChatPayException($msg, $xml);
        }
    }
}