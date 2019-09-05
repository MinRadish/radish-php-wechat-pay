# 微信支付

## 统一下单

~~~
    $wechatPay = new WeChatPay();
    $wechatPay = $wechatPay->orderUnify();
~~~

**$params说明**

~~~
$params = [
    'body' => '说明',
    'out_trade_no' => '订单号',
    'total_fee' => $amount * 100, // 金额
    'notify_url' => 'http://xxxx/api/order/notify', // 回调地址 
    'trade_type' => 'JSAPI',
    'openid' => $this->tempWechat->open_id,
    // 'openid' => 'oz4ZO5W4ws7MnDhpq1NtwMadkcAY',
];
$result = $wechatPay->orderUnify($params);
~~~

## 回调验证

- $params为微信请求参数

`$wechatPay = $wechatPay->notify($params);`
