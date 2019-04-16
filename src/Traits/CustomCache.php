<?php
namespace Radish\WeChatPay\Traits;


/**
* @author Radish 1004622952@qq.com 2019-03-15
* 自定义缓存
*/

trait CustomCache
{
    abstract public function cacheGet($key, $default = false);
    
    abstract public function cacheSet($key, $val, $timeout = 7140);
}