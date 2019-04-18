<?php
/**
 * 现金红包
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 10:55:00
 */

namespace Radish\WeChatPay\Traits;

use Radish\Network\Curl;

trait RedPacket
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
            'mch_id' => self::$mch_id,
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
        $params['sign'] = strtoupper($this->sign($params));
        $option = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSLCERTTYPE => 'PEM',
            CURLOPT_SSLCERT => self::$apiclient_cert,
            CURLOPT_SSLKEYTYPE => 'PEM',
            CURLOPT_SSLKEY => self::$apiclient_key,
        ];
        $params = $this->arrayToXml($params, false);
        $result = Curl::post($thsi->getRedPacket('send_red_pack'), $params, $option);

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
        $sign = $this->jointString($params) . $connector . 'key=' . self::$key;
        if ($type == 'md5') {
            $sign = md5($sign);
        }

        return $sign;
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
     * 获取请求连接
     * @param  string $key 连接类型
     * @return string      请求地址
     */
    protected function getRedPacket($key)
    {
        $urlMap = [
            //POST
            'send_red_pack' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack'
        ];

        return $urlMap[$key];
    }
}