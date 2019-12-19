# 现金红包

# **注意错误码**

~~~
    $wechatPay = new WeChatPay();
    $wechatPay->sendRedPackent($options)
~~~

**$options说明**

|字段|是否必填|说明|
|:--|:--|:--|
|mch_billno|是|商户订单号（每个订单号必须唯一。取值范围：0~9，a~z，A~Z接口根据商户订单号支持重入，如出现超时可再调用。|
|send_name|是|商户名称 红包发送者名称  注意：敏感词会被转义成字符*|
|open_id|是|open_id 微信号在微信公众号唯一标识|
|total_amount|是|付款金额，单位分|
|total_num|否(默认1)|红包发放总人数|
|wishing|是|红包祝福语注意：敏感词会被转义成字符*|
|act_name|是|活动名称注意：敏感词会被转义成字符*|
|remark|是|备注信息|
|scene_id|否|场景id发放红包使用场景，红包金额大于200或者小于1元时必传|

**scene_id取值范围**

~~~
PRODUCT_1:商品促销
PRODUCT_2:抽奖
PRODUCT_3:虚拟物品兑奖 
PRODUCT_4:企业内部福利
PRODUCT_5:渠道分润
PRODUCT_6:保险回馈
PRODUCT_7:彩票派奖
PRODUCT_8:税务刮奖
~~~

# 企业付款到用户零钱

~~~
    $wechatPay = new WeChatPay();
    $wechatPay->payForChange($options)
~~~

**$options说明**

|字段|是否必填|说明|
|:--|:--|:--|
|partner_trade_no|是|商户订单号，需保持唯一性(只能是字母或者数字，不能包含有其他字符)|
|openid|是|商户appid下，某用户的openid|
|check_name|是|NO_CHECK ： 不校验真实姓名 FORCE_CHECK ： 强校验真实姓名|
|re_user_name|否|收款用户真实姓名。如果check_name设置为FORCE_CHECK，则必填用户真实姓名|
|amount|是|企业付款金额，单位为分|
|desc|是|企业付款备注，必填。注意：备注中的敏感词会被转成字符*|

**内部预定义参数说明**

|字段|是否必填|说明|
|:--|:--|:--|
|mch_appid|是|申请商户号的appid或商户号绑定的appid|
|mchid|是|微信支付分配的商户号|
|device_info|否|微信支付分配的终端设备号|
|nonce_str|是|随机字符串，不长于32位|
|spbill_create_ip|是|该IP同在商户平台设置的IP白名单中的IP没有关联，该IP可传用户端或者服务端的IP。|