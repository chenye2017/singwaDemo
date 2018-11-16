<?php
/*
 * 定义类cryptAES 专用于AES加解密
 * 初始化时传入密钥长度、加密Key、初始向量、加密模式四个字段
 */
namespace app\common\lib;

class AES
{
    public $iv = null;
    public $key = null;
    public $bit = 128;
    private $cipher;

    /*public function __construct($bit, $key, $iv, $mode)
    {
        if(empty($bit) || empty($key) || empty($iv) || empty($mode))
        {
            return NULL;
        }

        $this->bit = $bit;
        $this->key = $key;
        $this->iv = $iv;
        $this->mode = $mode;

        switch($this->bit)
        {
            case 192 : $this->cipher = MCRYPT_RIJNDAEL_192; break;
            case 256 : $this->cipher = MCRYPT_RIJNDAEL_256; break;
            default : $this->cipher = MCRYPT_RIJNDAEL_128;
        }
        switch($this->mode)
        {
            case 'ecb' : $this->mode = MCRYPT_MODE_ECB; break;
            case 'cfb' : $this->mode = MCRYPT_MODE_CFB; break;
            case 'ofb' : $this->mode = MCRYPT_MODE_OFB; break;
            case 'nofb' : $this->mode = MCRYPT_MODE_NOFB; break;
            default : $this->mode = MCRYPT_MODE_CBC;
        }
    }*/

    public function __construct($key, $vi)
    {
        $this->key = $key;
        $this->vi = $vi;
    }

    /*
     * 加密数据并返回
     */
    public function encrypt($data)
    {
        $data = base64_encode(openssl_encrypt($this->cipher, $this->key, $data, $this->mode, $this->iv));
        return $data;
    }

    /*
     * 解密数据并返回
     */
    public function decrypt($data)
    {
        $data = openssl_decrypt($this->cipher, $this->key, base64_decode($data), $this->mode, $this->iv);
        $data = rtrim(rtrim($data), "\x00..\x1F");
        return $data;
    }

    public function myDecrypt($data)
    {
        $data = openssl_decrypt($data, 'aes-128-cbc', $this->key, OPENSSL_ZERO_PADDING, $this->vi);
        $data = rtrim(rtrim($data), "\x00..\x1F");
        return $data;
    }
}
