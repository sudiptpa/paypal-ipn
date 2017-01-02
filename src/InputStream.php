<?php

namespace PayPal\IPN;

class InputStream
{
    /**
     * @return string
     */
    public function getContents()
    {
        return file_get_contents('php://input');
    }
}
