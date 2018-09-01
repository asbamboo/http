<?php
namespace asbamboo\http;

use asbamboo\http\traits\MessageTrait;
use asbamboo\http\psr\PsrResponseInterface;

/**
 * 实现ResponseInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class Response implements ResponseInterface
{
    use MessageTrait;

    /**
     *
     * @var int
     */
    protected $status_code;

    /**
     *
     * @var string
     */
    protected $reason_phrase;

    /**
     *
     * @param StreamInterface $Body
     * @param int $status_code
     * @param array $headers
     * @param string $version
     */
    public function __construct(StreamInterface $Body, int $status_code = self::STATUS_OK, array $headers=[], string $version = '1.1')
    {
        $this->version      = $version;
        $this->Body         = $Body;
        $this->headers      = $headers;
        $this->status_code  = $status_code;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrResponseInterface::getStatusCode()
     */
    public function getStatusCode() : int
    {
        return $this->status_code;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrResponseInterface::getReasonPhrase()
     */
    public function getReasonPhrase(): string
    {
        if(empty($this->reason_phrase)){
            $this->reason_phrase    =  [
                // Informational 1xx
                self::STATUS_CONTINUE                                                     => 'Continue',
                self::STATUS_SWITCHING_PROTOCOLS                                          => 'Switching Protocols',
                self::STATUS_PROCESSING                                                   => 'Processing',            // RFC2518
                self::STATUS_EARLY_HINTS                                                  => 'Early Hints',
                // Successful 2xx
                self::STATUS_OK                                                           => 'OK',
                self::STATUS_CREATED                                                      => 'Created',
                self::STATUS_ACCEPTED                                                     => 'Accepted',
                self::STATUS_NON_AUTHORITATIVE_INFORMATION                                => 'Non-Authoritative Information',
                self::STATUS_NO_CONTENT                                                   => 'No Content',
                self::STATUS_RESET_CONTENT                                                => 'Reset Content',
                self::STATUS_PARTIAL_CONTENT                                              => 'Partial Content',
                self::STATUS_MULTI_STATUS                                                 => 'Multi-Status',          // RFC4918
                self::STATUS_ALREADY_REPORTED                                             => 'Already Reported',      // RFC5842
                self::STATUS_IM_USED                                                      => 'IM Used',               // RFC3229
                // Redirection 3xx
                self::STATUS_MULTIPLE_CHOICES                                             => 'Multiple Choices',
                self::STATUS_MOVED_PERMANENTLY                                            => 'Moved Permanently',
                self::STATUS_FOUND                                                        => 'Found',
                self::STATUS_SEE_OTHER                                                    => 'See Other',
                self::STATUS_NOT_MODIFIED                                                 => 'Not Modified',
                self::STATUS_USE_PROXY                                                    => 'Use Proxy',
                self::STATUS_RESERVED                                                     => 'Reserved',
                self::STATUS_TEMPORARY_REDIRECT                                           => 'Temporary Redirect',
                self::STATUS_PERMANENT_REDIRECT                                           => 'Permanent Redirect',    // RFC7238
                // Client Errors 4xx
                self::STATUS_BAD_REQUEST                                                  => 'Bad Request',
                self::STATUS_UNAUTHORIZED                                                 => 'Unauthorized',
                self::STATUS_PAYMENT_REQUIRED                                             => 'Payment Required',
                self::STATUS_FORBIDDEN                                                    => 'Forbidden',
                self::STATUS_NOT_FOUND                                                    => 'Not Found',
                self::STATUS_METHOD_NOT_ALLOWED                                           => 'Method Not Allowed',
                self::STATUS_NOT_ACCEPTABLE                                               => 'Not Acceptable',
                self::STATUS_PROXY_AUTHENTICATION_REQUIRED                                => 'Proxy Authentication Required',
                self::STATUS_REQUEST_TIMEOUT                                              => 'Request Timeout',
                self::STATUS_CONFLICT                                                     => 'Conflict',
                self::STATUS_GONE                                                         => 'Gone',
                self::STATUS_LENGTH_REQUIRED                                              => 'Length Required',
                self::STATUS_PRECONDITION_FAILED                                          => 'Precondition Failed',
                self::STATUS_PAYLOAD_TOO_LARGE                                            => 'Payload Too Large',
                self::STATUS_URI_TOO_LONG                                                 => 'URI Too Long',
                self::STATUS_UNSUPPORTED_MEDIA_TYPE                                       => 'Unsupported Media Type',
                self::STATUS_RANGE_NOT_SATISFIABLE                                        => 'Range Not Satisfiable',
                self::STATUS_EXPECTATION_FAILED                                           => 'Expectation Failed',
                self::STATUS_IM_A_TEAPOT                                                  => 'I\'m a teapot',                                               // RFC2324
                self::STATUS_MISDIRECTED_REQUEST                                          => 'Misdirected Request',                                         // RFC7540
                self::STATUS_UNPROCESSABLE_ENTITY                                         => 'Unprocessable Entity',                                        // RFC4918
                self::STATUS_LOCKED                                                       => 'Locked',                                                      // RFC4918
                self::STATUS_FAILED_DEPENDENCY                                            => 'Failed Dependency',                                           // RFC4918
                self::STATUS_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL    => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
                self::STATUS_UPGRADE_REQUIRED                                             => 'Upgrade Required',                                            // RFC2817
                self::STATUS_PRECONDITION_REQUIRED                                        => 'Precondition Required',                                       // RFC6585
                self::STATUS_TOO_MANY_REQUESTS                                            => 'Too Many Requests',                                           // RFC6585
                self::STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE                              => 'Request Header Fields Too Large',                             // RFC6585
                self::STATUS_UNAVAILABLE_FOR_LEGAL_REASONS                                => 'Unavailable For Legal Reasons',                               // RFC7725
                // Server Errors 5xx
                self::STATUS_INTERNAL_SERVER_ERROR                                        => 'Internal Server Error',
                self::STATUS_NOT_IMPLEMENTED                                              => 'Not Implemented',
                self::STATUS_BAD_GATEWAY                                                  => 'Bad Gateway',
                self::STATUS_SERVICE_UNAVAILABLE                                          => 'Service Unavailable',
                self::STATUS_GATEWAY_TIMEOUT                                              => 'Gateway Timeout',
                self::STATUS_VERSION_NOT_SUPPORTED                                        => 'HTTP Version Not Supported',
                self::STATUS_VARIANT_ALSO_NEGOTIATES                                      => 'Variant Also Negotiates',                                     // RFC2295
                self::STATUS_INSUFFICIENT_STORAGE                                         => 'Insufficient Storage',                                        // RFC4918
                self::STATUS_LOOP_DETECTED                                                => 'Loop Detected',                                               // RFC5842
                self::STATUS_NOT_EXTENDED                                                 => 'Not Extended',                                                // RFC2774
                self::STATUS_NETWORK_AUTHENTICATION_REQUIRED                              => 'Network Authentication Required',                             // RFC6585
            ][$this->getStatusCode()];
        }

        return $this->reason_phrase;
    }

    /**
     * 发送header到客户端
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ResponseInterface::sendHeaders()
     */
    public function sendHeaders() : void
    {
        header(sprintf('HTTP/%s %s %s', $this->version, $this->status_code, $this->getReasonPhrase()), true, $this->status_code);

        foreach($this->getHeaders() AS $name => $values ){
            foreach((array)$values as $value) {
                header($name.': '.$value, false, $this->status_code);
            }
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ResponseInterface::sendContent()
     */
    public function sendContent() : void
    {
        echo $this->getBody();
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\ResponseInterface::send()
     */
    public function send() : void
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\http\psr\PsrResponseInterface::withStatus()
     */
    public function withStatus(int $code, string $reason_phrase = '') : PsrResponseInterface
    {
        $New                = clone $this;
        $New->status_code   = $code;
        $New->reason_phrase = $reason_phrase;
        return $New;
    }
}
