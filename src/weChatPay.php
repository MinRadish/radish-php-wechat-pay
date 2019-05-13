<?php
/**
 * 
 * @authors Radish (1004622952@qq.com)
 * @date    2019-04-16 10:47:49
 */

namespace Radish\WeChatPay;

abstract class WeChatPay
{
    protected static $mchId = '';
    protected static $appId = '';
    protected static $appSecret = '';
    protected static $key = '';
    protected static $serverIp = '';
    protected static $apiclientCert = '';
    protected static $apiclientKey = '';

    use Traits\Common;
    use Traits\AccessToken;
    use Traits\JsSdkConfig;
    use Traits\EnterprisePay;

    public function __construct(array $options = [])
    {
        foreach ($options as $key => $val) {
            if (property_exists($this, $key)) {
                switch ($key) {
                    case 'mchId':
                        self::$mchId = $val;
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
                    case 'apiclientCert':
                            self::$apiclientCert = $val;
                            break;
                    case 'apiclientKey':
                            self::$apiclientKey = $val;
                            break;
                }
            }
        }
    }
}