<?php

namespace Netgen\HtmlPdfApi\Exception;

use Exception;

class WrongFileExtensionException extends Exception {

    /**
     * Constructor
     *
     * @param string $message   Message of the exception
     */
    public function __construct ($message)
    {
        parent::__construct($message);
    }

} 