<?php

namespace PayPal\IPN;

class IPNMessage
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        if (!is_array($data)) {
            $data = $this->extractDataFromRawPostDataString($data);
        }

        $this->data = $data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $str = '';

        foreach ($this->data as $k => $v) {
            $str .= sprintf('%s=%s&', $k, rawurlencode($v));
        }

        return rtrim($str, '&');
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $value = '';

        if (isset($this->data[$key])) {
            $value = $this->data[$key];
        }

        return $value;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    private function extractDataFromRawPostDataString($string)
    {
        $data = [];

        $array = preg_split('/&/', $string, null, PREG_SPLIT_NO_EMPTY);

        foreach ($array as $each) {
            list($k, $v) = explode('=', $each);

            $data[$k] = rawurldecode($v);
        }

        return $data;
    }
}
