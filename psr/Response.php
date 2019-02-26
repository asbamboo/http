<?php
namespace asbamboo\http\psr;

use asbamboo\http\Constant;
use asbamboo\http\psr\traits\MessageTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\MessageInterface;

/**
 * 实现ResponseInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月25日
 */
class Response implements MessageInterface, ResponseInterface
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
    public function __construct(StreamInterface $Body, int $status_code = Constant::STATUS_OK, array $headers=[], string $version = '1.1')
    {
        $this->version      = $version;
        $this->Body         = $Body;
        $this->headers      = $headers;
        $this->status_code  = $status_code;
    }

    /**
     *
     * {@inheritDoc}
     * @see ResponseInterface::getStatusCode()
     */
    public function getStatusCode() : int
    {
        return $this->status_code;
    }

    /**
     *
     * {@inheritDoc}
     * @see ResponseInterface::getReasonPhrase()
     */
    public function getReasonPhrase(): string
    {
        if(empty($this->reason_phrase)){
            $this->reason_phrase    =  [
                // Informational 1xx
                Constant::STATUS_CONTINUE                                                     => 'Continue',
                Constant::STATUS_SWITCHING_PROTOCOLS                                          => 'Switching Protocols',
                Constant::STATUS_PROCESSING                                                   => 'Processing',            // RFC2518
                Constant::STATUS_EARLY_HINTS                                                  => 'Early Hints',
                // Successful 2xx
                Constant::STATUS_OK                                                           => 'OK',
                Constant::STATUS_CREATED                                                      => 'Created',
                Constant::STATUS_ACCEPTED                                                     => 'Accepted',
                Constant::STATUS_NON_AUTHORITATIVE_INFORMATION                                => 'Non-Authoritative Information',
                Constant::STATUS_NO_CONTENT                                                   => 'No Content',
                Constant::STATUS_RESET_CONTENT                                                => 'Reset Content',
                Constant::STATUS_PARTIAL_CONTENT                                              => 'Partial Content',
                Constant::STATUS_MULTI_STATUS                                                 => 'Multi-Status',          // RFC4918
                Constant::STATUS_ALREADY_REPORTED                                             => 'Already Reported',      // RFC5842
                Constant::STATUS_IM_USED                                                      => 'IM Used',               // RFC3229
                // Redirection 3xx
                Constant::STATUS_MULTIPLE_CHOICES                                             => 'Multiple Choices',
                Constant::STATUS_MOVED_PERMANENTLY                                            => 'Moved Permanently',
                Constant::STATUS_FOUND                                                        => 'Found',
                Constant::STATUS_SEE_OTHER                                                    => 'See Other',
                Constant::STATUS_NOT_MODIFIED                                                 => 'Not Modified',
                Constant::STATUS_USE_PROXY                                                    => 'Use Proxy',
                Constant::STATUS_RESERVED                                                     => 'Reserved',
                Constant::STATUS_TEMPORARY_REDIRECT                                           => 'Temporary Redirect',
                Constant::STATUS_PERMANENT_REDIRECT                                           => 'Permanent Redirect',    // RFC7238
                // Client Errors 4xx
                Constant::STATUS_BAD_REQUEST                                                  => 'Bad Request',
                Constant::STATUS_UNAUTHORIZED                                                 => 'Unauthorized',
                Constant::STATUS_PAYMENT_REQUIRED                                             => 'Payment Required',
                Constant::STATUS_FORBIDDEN                                                    => 'Forbidden',
                Constant::STATUS_NOT_FOUND                                                    => 'Not Found',
                Constant::STATUS_METHOD_NOT_ALLOWED                                           => 'Method Not Allowed',
                Constant::STATUS_NOT_ACCEPTABLE                                               => 'Not Acceptable',
                Constant::STATUS_PROXY_AUTHENTICATION_REQUIRED                                => 'Proxy Authentication Required',
                Constant::STATUS_REQUEST_TIMEOUT                                              => 'Request Timeout',
                Constant::STATUS_CONFLICT                                                     => 'Conflict',
                Constant::STATUS_GONE                                                         => 'Gone',
                Constant::STATUS_LENGTH_REQUIRED                                              => 'Length Required',
                Constant::STATUS_PRECONDITION_FAILED                                          => 'Precondition Failed',
                Constant::STATUS_PAYLOAD_TOO_LARGE                                            => 'Payload Too Large',
                Constant::STATUS_URI_TOO_LONG                                                 => 'URI Too Long',
                Constant::STATUS_UNSUPPORTED_MEDIA_TYPE                                       => 'Unsupported Media Type',
                Constant::STATUS_RANGE_NOT_SATISFIABLE                                        => 'Range Not Satisfiable',
                Constant::STATUS_EXPECTATION_FAILED                                           => 'Expectation Failed',
                Constant::STATUS_IM_A_TEAPOT                                                  => 'I\'m a teapot',                                               // RFC2324
                Constant::STATUS_MISDIRECTED_REQUEST                                          => 'Misdirected Request',                                         // RFC7540
                Constant::STATUS_UNPROCESSABLE_ENTITY                                         => 'Unprocessable Entity',                                        // RFC4918
                Constant::STATUS_LOCKED                                                       => 'Locked',                                                      // RFC4918
                Constant::STATUS_FAILED_DEPENDENCY                                            => 'Failed Dependency',                                           // RFC4918
                Constant::STATUS_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL    => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
                Constant::STATUS_UPGRADE_REQUIRED                                             => 'Upgrade Required',                                            // RFC2817
                Constant::STATUS_PRECONDITION_REQUIRED                                        => 'Precondition Required',                                       // RFC6585
                Constant::STATUS_TOO_MANY_REQUESTS                                            => 'Too Many Requests',                                           // RFC6585
                Constant::STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE                              => 'Request Header Fields Too Large',                             // RFC6585
                Constant::STATUS_UNAVAILABLE_FOR_LEGAL_REASONS                                => 'Unavailable For Legal Reasons',                               // RFC7725
                // Server Errors 5xx
                Constant::STATUS_INTERNAL_SERVER_ERROR                                        => 'Internal Server Error',
                Constant::STATUS_NOT_IMPLEMENTED                                              => 'Not Implemented',
                Constant::STATUS_BAD_GATEWAY                                                  => 'Bad Gateway',
                Constant::STATUS_SERVICE_UNAVAILABLE                                          => 'Service Unavailable',
                Constant::STATUS_GATEWAY_TIMEOUT                                              => 'Gateway Timeout',
                Constant::STATUS_VERSION_NOT_SUPPORTED                                        => 'HTTP Version Not Supported',
                Constant::STATUS_VARIANT_ALSO_NEGOTIATES                                      => 'Variant Also Negotiates',                                     // RFC2295
                Constant::STATUS_INSUFFICIENT_STORAGE                                         => 'Insufficient Storage',                                        // RFC4918
                Constant::STATUS_LOOP_DETECTED                                                => 'Loop Detected',                                               // RFC5842
                Constant::STATUS_NOT_EXTENDED                                                 => 'Not Extended',                                                // RFC2774
                Constant::STATUS_NETWORK_AUTHENTICATION_REQUIRED                              => 'Network Authentication Required',                             // RFC6585
            ][$this->getStatusCode()];
        }
        if(empty($this->reason_phrase)){
            $this->reason_phrase    = "System Error";
        }
        return $this->reason_phrase;
    }

    /**
     *
     * {@inheritDoc}
     * @see ResponseInterface::withStatus()
     */
    public function withStatus(/*int*/ $code, /*string*/ $reason_phrase = '') : ResponseInterface
    {
        $New                = clone $this;
        $New->status_code   = $code;
        $New->reason_phrase = $reason_phrase;
        return $New;
    }
}
