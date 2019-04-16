<?php
/**
 * 
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 10:47:49
 */

namespace Radish\WeChatPay;

class ClassName extends AnotherClass
{
    protected static $mch_id = '';
    protected static $wxappid = '';
    protected static $key = '';
    protected static $serverIp = '';

    use Traits\Common;
    use Traits\RedPacket;
    use Traits\AccessToken;
    use Traits\JsSdkConfig;

    public function __construct(array $options = [])
    {
        foreach ($options as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }
}