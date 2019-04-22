# jsApi SDK 验证生成

~~~
    $wechatPay = new WeChatPay();
    $wechatPay->signature($params)
~~~

**$params说明**

~~~
    $weCharPay = new WeChatPay;
    $array = [
        'timestamp' => time(), //生成签名的时间戳
        'noncestr' => $weCharPay->getRandomStr(15),//生成签名的随机串
        'url' => $request->url(true), //当前页面完整路径
    ];
    $array['signature'] = $weCharPay->signature($array);
    * wx.Config中（noncestr）S必须大写 *
    $array['nonceStr'] = $array['noncestr']; //生成签名的随机串
    unset($array['url']);
    $array['jsApiList'] = ['updateTimelineShareData']; // 需要使用的JS接口列表
    $array['debug'] = true; //开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    $array['appId'] = 公众号appid;
    $jsApiConfig = json_encode($array);
~~~

**JS写法**
~~~
    wx.config({:$jsApiConfig});
    wx.ready(function() {
        // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
        <!-- 死活没用还不知道是我不会用（首次加载有效果） -->
        // wx.updateTimelineShareData({ 
        //     title: '信息Radish', // 分享标题
        //     link: 'http://app5.minradish.cn/index', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        //     imgUrl: 'http://qiniu-cdn.minradish.cn/user/face/1-1552959187.jpg', // 分享图标
        //     success: function () {
        //         alert('1');
        //     }
        // });

        wx.onMenuShareTimeline({
            title: '信息', // 分享标题
            link: 'http://app5.minradish.cn/index/index/home', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://qiniu-cdn.minradish.cn/user/face/1-1552959187.jpg', // 分享图标
            success: function () {
            // 用户点击了分享后执行的回调函数
                alert('2');
            },
            fail: function () {
                alert('2 fail');
            },
            cancel: function () {
                alert('2 cancel');
            },
            complete: function () {
                alert('2 complete');
            },
            trigger: function () {
                alert('2 complete');
            }
        });
    });

    wx.error(function(res){
        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
        alert(res);
    });
~~~

# chooseCard

~~~

~~~

#批量添加卡券addCard
~~~
$cardId = 'p1zuz1I14xfEtC0XMl6muookUsX8'; //卡券ID
$params = [
    // 'code' => '', //卡券code
    'timestamp' => time(),
    'nonce_str' => $weChatPay->getRandomStr(15),
    'card_id' => $cardId,
    'openid' => $this->user->open_id,
];
$params['signature'] = $weChatPay->cardSign($params);
$params = json_encode($params);
~~~
##js 
~~~
    wx.config({:$jsApiConfig});
    wx.ready(function() {
        
    });
    $(".checkCard").click(function () {
        wx.addCard({
            cardList: [{
                cardId: "{:$cardId}",
                cardExt: '{:$params}',
            }], // 需要添加的卡券列表
            success: function (res) {
                console.log(res);
                var cardList = res.cardList; // 添加的卡券列表信息
            }
        });
    })
~~~