<?php
/**
 * 
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 10:47:49
 */

namespace Radish\WeChatPay;

abstract class WeChatPay
{
    protected static $mch_id = '';
    protected static $appId = '';
    protected static $appSecret = '';
    protected static $key = '';
    protected static $serverIp = '';
    protected static $apiclient_cert = '';
    protected static $apiclient_key = '';

    use Traits\Common;
    use Traits\RedPacket;
    use Traits\AccessToken;
    use Traits\JsSdkConfig;

    public function __construct(array $options = [])
    {
        foreach ($options as $key => $val) {
            if (property_exists($this, $key)) {
                switch ($key) {
                    case 'mch_id':
                        self::$mch_id = $val;
                        break;
                    case 'appId':
                            self::$appId = $val;
                            break;
                    case 'appSecret':
                            self::$appSecret = $val;
                            break;
                    case 'key':
                            self::$key = $val;
                            break;
                    case 'serverIp':
                            self::$serverIp = $val;
                            break;
                    case 'apiclient_cert':
                            self::$apiclient_cert = $val;
                            break;
                    case 'apiclient_key':
                            self::$apiclient_key = $val;
                            break;
                }
            }
        }
    }
}