<?php

namespace Sujip\PayPal\Notification;

/**
 * Class Payload
 * @package  Sujip\PayPal\Notification
 */
class Payload
{
    /**
     * @var array
     */
    private $payload = [];

    /**
     * @param $payload
     */
    public function __construct($payload)
    {
        if (!is_array($payload)) {
            $payload = $this->parseRawString($payload);
        }

        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return http_build_query($this->all(), null, '&');
    }

    /**
     * Find a value from array with given key.
     *
     * @param $key
     * @return mixed
     */
    public function find($key)
    {
        if (isset($this->payload[$key])) {
            return $this->payload[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->payload;
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    private function parseRawString($string)
    {
        parse_str($string, $data);

        return $data;
    }
}
