<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\BrowserKit;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Response object.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class Response
{

    /**
     * @var \Symfony\Component\HttpFoundation\ResponseHeaderBag
     */
    public $headers;

    protected $content;
    protected $version;
    protected $status;

    protected $arrayHeaders;

    /**
     * Constructor.
     *
     * The headers array is a set of key/value pairs. If a header is present multiple times
     * then the value is an array of all the values.
     *
     * @param string  $content The content of the response
     * @param integer $status  The response status code
     * @param array   $headers An array of headers
     *
     * @api
     */
    public function __construct($content = '', $status = 200, array $headers = array())
    {
        $this->arrayHeaders = $headers;
        $this->headers = new ResponseHeaderBag($headers);
        $this->status = $status;
        $this->content = $content;
    }

    /**
     * Converts the response object to string containing all headers and the response content.
     *
     * @return string The response with headers and content
     */
    public function __toString()
    {
        //TODO: probably here we don't need to clone headers?
        /** @var \Symfony\Component\HttpFoundation\ResponseHeaderBag $headers  */
        $headers = clone $this->headers;
        $headers->remove('date');
        $headers->remove('cache-control');

        //TODO: don't like this. Maybe replace this in tests so they will not fail?
        //TODO: also there are identation of header values in ResponseHeaderBag. Do we need this?
        return str_replace("\r", '', strtolower($headers))."\n".$this->getContent();
    }

    /**
     * Returns the build header line.
     *
     * @param string $name  The header name
     * @param string $value The header value
     *
     * @return string The built header line
     */
    protected function buildHeader($name, $value)
    {
        return sprintf("%s: %s\n", $name, $value);
    }

    /**
     * Gets the response content.
     *
     * @return string The response content
     *
     * @api
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Gets the response status code.
     *
     * @return integer The response status code
     *
     * @api
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets the response headers.
     *
     * @return array The response headers
     *
     * @api
     */
    public function getHeaders()
    {
        return $this->arrayHeaders;
    }

    /**
     * Gets a response header.
     *
     * @param string  $header The header name
     * @param Boolean $first  Whether to return the first value or all header values
     *
     * @return string|array The first header value if $first is true, an array of values otherwise
     */
    public function getHeader($header, $first = true)
    {
        return $this->headers->get($header, null, $first);
    }
}
