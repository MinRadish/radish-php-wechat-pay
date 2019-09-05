# 微信支付
*需自定义一个类并继承 Radish\WeChatPay\WeChatPay重写构造函数进行相关配置*

*自定义相关参数保存调用接口凭证*

**public function cacheGet($key, $default = false);**

**public function cacheSet($key, $val, $timeout = 7140);**

## api示例说明
~~~
    $wechatPay = new WeChatPay();
    $wechatPay->sendRedPackent($options)
~~~

**options 示例**

~~~
    $mchbillno = $wechatPay->orderNo('建议相关表ID | 也可不传');
    $params = [
        'mch_billno' => $mchbillno,//'商户订单号（每个订单号必须唯一。取值范围：0~9，a~z，A~Z接口根据商户订单号支持重入，如出现超时可再调用。'
        'send_name' => 'Radish',//'商户名称 红包发送者名称  注意：敏感词会被转义成字符*'
        'open_id' => open_id,//'open_id 微信号在微信公众号唯一标识'
        'total_amount' => 0.5 * 100,//'付款金额，单位分'
        'wishing' => '',//'红包祝福语注意：敏感词会被转义成字符*'
        'act_name' => '',//'活动名称注意：敏感词会被转义成字符*'
        'remark' => '',//'备注信息'
        'scene_id' => '',//'场景id发放红包使用场景，红包金额大于200或者小于1元时必传'
    ];
~~~

# 配置相关

~~~
根据个人仍情况整理
[
    'appId' => '微信公众号APPID',
    'appSecret' => '微信公众号APPSECRET',
    'pay' => [
        'mchId' => '商户号',
        'key' => '商户秘钥',
        'serverIp' => '服务器IP',
        'apiclientCert' => 证书路径, 
        'apiclientKey' => 证书路径, 
    ],
];
~~~