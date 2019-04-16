<?php
namespace Radish\WeChatPay\Exception;

/**
* @author Radish 1004622952@qq.com 2019-03-15
* 微信公众号API错误异常类
*/

class WeChatPayException extends \Exception
{
    protected $message;
    protected $result;

    public function __construct($message, $result)
    {
        $this->message = $message;
        $this->result = $result;
        $this->createLog();
    }

    public function createLog()
    {
        $path = $_SERVER['DOCUMENT_ROOT'];
        if (is_dir($path)) {
            $file = $path . '/WeChatPay.log';
            $time = date('Y-m-d H:i:s');
            file_put_contents($file, $time . PHP_EOL . $this->result() . PHP_EOL . 'message:' . $this->message . PHP_EOL, FILE_APPEND);
        }
    }

    public function message()
    {
        return $this->message;
    }

    public function result()
    {
        return $this->result;
    }
}