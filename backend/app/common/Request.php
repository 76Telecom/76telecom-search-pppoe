<?php

namespace Telecom76;

/**
 * Class Request cURL
 * @author @CaioAgiani
 *
 * @package 76Telecom
 * @example ...
 */
class Request
{
    /**
     * @var string Proxy - Full proxy
     */
    private $proxyFull;

    /**
     * @var string Proxy - User proxy
     */
    private $proxyUser;

    /**
     * @var string Proxy - Pass proxy
     */
    private $proxyPass;

    /**
     * @api Constructor of API class.
     *
     * @param string define private strings :: $proxyFull, $proxyUser, $proxyPass 
     */
    function __construct()
    {
        $this->proxyUser = CONFIG["PROXY_USER"];
        $this->proxyPass = CONFIG["PROXY_PASS"];
        $this->proxyFull = "lum-customer-{$this->proxyUser}-zone-static-session-" . mt_rand() . "-country-br:{$this->proxyPass}@zproxy.lum-superproxy.io:22225";
    }

    /**
     * Contruct cUrl browser 
     *
     * @param  mixed $ur, $post, $header, $http_header, $decode, $proxy
     * @return void
     */
    public function Web($url, $post = null, $header = [], $http_header = false, $decode = false, $proxy = false)
    {
        $ch = curl_init($url);

        $ex = explode("/", $url);

        if ($http_header) curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . "/cookie/" . $ex[3] . "cache.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . "/cookie/" . $ex[3] . "cache.txt");

        if ($proxy) curl_setopt($ch, CURLOPT_PROXY, $this->proxyFull);

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        $return = !$decode ? curl_exec($ch) : json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $return;
    }

    /**
     * Extract information
     * @property Web
     *
     * @param  mixed $string, $start, $end, $value
     * @return void
     */
    public function Extract($string, $start, $end, $value)
    {
        $str = explode($start, $string);
        $str = explode($end, $str[$value]);

        return $str[0];
    }

    /**
     * Save file @log
     *
     * @param  mixed $file_extension, $context, $type
     * @return void
     */
    public function Save($file_extension, $context, $type)
    {
        $file = fopen($file_extension, $type);
        fwrite($file, $context);
        fclose($file);

        return true;
    }

    /**
     * Clear cookies
     *
     * @return void
     */
    public function Clear($boolean, $file) 
    {
        if ($boolean == true) file_exists(getcwd() . '/cookie/' . $file) ? unlink(getcwd() . '/cookie/' . $file) : null;

        return true;
    }
}