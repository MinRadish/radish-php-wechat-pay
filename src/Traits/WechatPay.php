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
     * @return         [description]
     */
    public function orderUnify(array $options)
    {
        $params = [
            'appid' => self::$appId,
            'mch_id' => self::$mchId,
            // 'device_info',
            'nonce_str' => $this->getRandomStr(),
        ];
        $params = array_merge($params, $options);

        return $this->sendResult($params, 'order_unify');
    }
}