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

    use Traits\Common;
    use Traits\RedPacket;

    public function __construct(array $options = [])
    {
        foreach ($options as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }
}