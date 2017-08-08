<?php

namespace Netgen\HtmlPdfApi\Tests\Exception;

use Netgen\HtmlPdfApi\Exception\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class FileNotFoundExceptionTest extends TestCase
{
    /**
     * @expectedException \Netgen\HtmlPdfApi\Exception\FileNotFoundException
     * @expectedExceptionMessage File not found
     */
    public function testException()
    {
        throw new FileNotFoundException("File not found");
    }
}
