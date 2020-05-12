# 微信支付

## 统一下单

~~~
    $wechatPay = new WeChatPay();
    $wechatPay = $wechatPay->orderUnify($params);
~~~

### 支付场景-公众号支付


**一：$params说明**

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

**二：jsapi参数**

`返回jsSdkConfig请查看当前路径下JsSdkConfig.md`

- 返回给前端

~~~
$signParams = [
    'appId' => config('wechat.appId'),
    'timeStamp' => time() . '',
    'nonceStr' => $wechatPay->getRandomStr(16),
    'package' => 'prepay_id=' . $result['prepay_id'],
    'signType' => 'MD5',
];
$signParams['paySign'] = $wechatPay->sign($signParams);
unset($signParams['appId']);

return $signParams;
~~~

### 支付场景-扫码支付

- QrCode 为基于生成二维码的composer包类二次封装类

~~~
$params = [
    'body' => $body,
    'out_trade_no' => 'N' . $this->getAttribute('pay_no'),
    'total_fee' => $amount * 100, // 金额
    'notify_url' => 'http://xxxx/api/order/notify', // 回调地址 
    'trade_type' => 'NATIVE',
    'spbill_create_ip' => request()->ip(),
    // 'openid' => $this->tempWechat->open_id,
];
$result = $wechatPay->orderUnify($params);
return 'data:image/png;base64,' . base64_encode(QrCode::qrCodeBasc($result['code_url']));
~~~


## 回调验证

- $params为微信请求参数

`$wechatPay = $wechatPay->notify($params);`
