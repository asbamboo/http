<?php
namespace asbamboo\http\psr;

/**
 * 本接口遵守psr7规范[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md]
 * 传入的服务器端HTTP请求的表示
 *
 * 根据HTTP规范，该接口包括下列各属性:
 * - 协议版本[Protocol version]
 * - HTTP方法[HTTP method]
 * - URI
 * - 消息头[Headers]
 * - 消息体[Message body]
 *
 * 此外，它封装了从CGI和/或PHP环境到应用程序的所有数据，包括:
 * - $_SERVER中表示的值。
 * - 提供的任何cookie(通常通过$_COOKIE)
 * - 查询字符串参数(通常通过$_GET或通过parse_str()解析)
 * - 上传文件，如果有的话(以$_FILES表示)
 * - 反序列化的体参数(一般为$_POST)
 *
 * $_SERVER值必须被视为不可变，因为它们在请求时表示应用程序状态;因此，没有提供允许修改这些值的方法。
 * 其他值提供这样的方法，因为它们可以从$_SERVER或请求体恢复，并且在应用程序期间可能需要处理(例如，可以根据内容类型对body参数进行反序列化)。
 *
 * 此外，该接口还识别了一个请求来派生和匹配其他参数(例如，通过URI路径匹配，解密cookie值，反序列化非表单编码的正文内容，匹配授权头给用户等)。这些参数存储在“属性”属性中。
 *
 * 请求被认为是不可改变的;所有可能更改状态的方法都必须实现，这样它们才能保留当前消息的内部状态，并返回包含已更改状态的实例。
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月18日
 */
interface PsrServerRequestInterface extends PsrRequestInterface
{
    /**
     * 获取服务器参数。
     *
     * 获取与传入请求环境相关的数据，这些数据通常来自PHP的全局变量$_SERVER。数据并不是必须来自$_SERVER。
     *
     * @return array
     */
    public function getServerParams() : array;

    /**
     * 获取cookie
     *
     * 获取客户机发送到服务器的cookie。
     *
     * 数据必须与$_COOKIE的结构兼容。
     *
     * @return array
     */
    public function getCookieParams() : array;

    /**
     * 返回一个带有指定cookie的实例。
     *
     * 数据不是必须来自$_COOKIE超全局变量，但必须与$_COOKIE的结构兼容。通常，这些数据将在实例化时注入。
     *
     * 此方法不能更新请求实例的相关Cookie头，也不能更新服务器参数中的相关值。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有更新的cookie值的实例。
     *
     * @param array $cookies 表示cookie的键/值对数组
     * @return static
     */
    public function withCookieParams(array $cookies) : self;

    /**
     * 获取查询字符串参数[query string]。
     *
     * 获取反序列化的查询字符串参数(如果有的话)。
     *
     * 注意:查询参数可能与URI或服务器参数不同步。如果您需要确保只获得原始值，您可能需要从“getUri()->getQuery()”或“QUERY_STRING”服务器参数中解析查询字符串。
     *
     * @return array
     */
    public function getQueryParams() : array;

    /**
     * 返回一个具有指定查询字符串[query_string]参数的实例。
     *
     * 这些值应该在传入请求的过程中保持不变。
     * 它们可能在实例化过程中被注入，比如PHP的$_GET超级全局变量，或者可能来自其他值，比如URI。
     * 在从URI解析参数的情况下，数据必须与PHP的parse_str()相兼容，以达到处理重复查询参数的目的，以及如何处理嵌套集合。
     *
     * 设置查询字符串参数不能更改请求存储的URI，也不能更改服务器参数中的值。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有更新后的查询字符串参数的实例。
     *
     * @param array $query 查询字符串参数的数组，通常为$_GET。
     * @return static
     */
    public function withQueryParams(array $querys) : self;

    /**
     * 获取规范化文件上传数据。
     *
     * 该方法在规范化树中返回上传元数据，每个叶子都有一个UploadedFileInterface的实例。
     *
     * 这些值可以在实例化期间从$_FILES或消息体中编写，也可以通过withUploadedFiles()注入。
     *
     * @return array UploadedFileInterface实例的数组树;如果没有数据，则必须返回空数组。
     */
    public function getUploadedFiles() : array;

    /**
     * 使用指定的上传文件创建一个新实例。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有更新后的主体参数的实例。
     *
     * @param array $uploaded_files UploadedFileInterface实例的数组树。
     * @return static
     * @throws \InvalidArgumentException 如果提供了无效结构
     */
    public function withUploadedFiles(array $uploaded_files) : self;

    /**
     * 获取请求主体中提供的任何参数。
     *
     * 如果请求内容类型是application/x-www-form-urlencode或multipart/form-data，并且请求方法是POST，则该方法必须返回$_POST的内容。
     * 否则，该方法可能返回任何反序列化请求体内容的结果;当解析返回结构化内容时，潜在类型必须是数组或对象。空值表示没有正文内容。
     *
     * @return null|array|object 反序列化的身体参数，如果有的话。它们通常是一个数组或对象。.
     */
    public function getParsedBody();

    /**
     * 返回一个具有指定主体参数的实例。
     *
     * 这些可以在实例化期间注入。
     *
     * 如果请求内容类型是application/x-www-form-urlencode或multipart/form-data，请求方法是POST，则使用此方法只注入$_POST的内容。
     *
     * 数据不需要来自$_POST，但必须是对请求体内容进行反序列化的结果。Deserialization/parsing返回结构化数据，因此，该方法只接受数组或对象，如果没有可用的解析，则只接受null值。
     *
     * 例如，如果内容协商确定请求数据是一个JSON有效负载，则可以使用该方法来创建带有反序列化参数的请求实例。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有更新后的主体参数的实例。
     *
     * @param null|array|object $data 反序列化的body数据。这通常是在数组或对象中。
     * @return static
     * @throws \InvalidArgumentException 如果提供了不支持的参数类型
     */
    public function withParsedBody($data) : self;

    /**
     * 获取来自请求的属性。
     *
     * 请求“属性”可用于允许注入来自请求的任何参数:例如，
     * 路径匹配操作的结果;
     * 解密cookie的结果;
     * 反序列化非表单编码消息体的结果;
     * 属性将是应用程序和请求特定的，并且可以是可变的。
     *
     * @return array 来自请求的属性。
     */
    public function getAttributes() : array;

    /**
     * 获取单个派生请求属性。
     *
     * 检索getAttributes()中描述的单个派生请求属性。如果属性没有预先设置，则返回所提供的默认值。
     *
     * 该方法排除了hasAttribute()方法的需要，因为它允许指定一个默认值，如果没有找到该属性，则返回。
     *
     * @see getAttributes()
     * @param string $name 属性名称。
     * @param mixed $default 如果属性不存在，则返回默认值。
     * @return mixed
     */
    public function getAttribute(string $name, $default = null);

    /**
     * 使用指定的派生请求属性返回一个实例。
     *
     * 该方法允许在getAttributes()中设置一个单独的派生请求属性。
     *
     * 必须以这样的方式实现此方法，以保持消息的不可变性，并且必须返回具有更新属性的实例。
     *
     * @see getAttributes()
     * @param string $name 属性名称
     * @param mixed $value 属性的值
     * @return static
     */
    public function withAttribute(string $name, $value) : self;

    /**
     * 返回删除指定的派生请求属性的实例。
     *
     * 该方法允许删除getAttributes()中描述的单个派生请求属性。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回删除该属性的实例。
     *
     * @see getAttributes()
     * @param string $name 属性名称。
     * @return static
     */
    public function withoutAttribute(string $name) : self;
}
