<?php
namespace asbamboo\http\psr;

/**
 * 本接口遵守psr7规范[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md]
 *
 * 表示URI的对象接口。
 *
 * 这个接口的意思是根据 RFC 3986来表示uri，并为大多数常用操作提供方法。与uri一起工作的附加功能可以在接口或外部提供。
 * 它的主要用途是用于HTTP请求，但也可以用于其他上下文。
 *
 * 该接口的实例被认为是不可变的;所有可能更改状态的方法都必须实现，这样它们才能保留当前实例的内部状态，并返回包含已更改状态的实例。
 *
 * 通常，在请求消息中也会出现Host header。
 * 对于服务器端请求，该方案通常可以在服务器参数中发现。
 *
 * @link http://tools.ietf.org/html/rfc3986 (the URI specification)
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月18日
 */
interface PsrUriInterface
{
    /**
     * 获取URI的scheme组件。
     *
     * 如果不存在scheme，则该方法必须返回空字符串。
     *
     * 返回的值必须被规范化为小写，遵守RFC3986第3.1节。
     *
     * 后面的“:”字符不是该方案的一部分，不能添加。
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string URI scheme.
     */
    public function getScheme() : string;

    /**
     * 获取URI的授权组件。
     *
     * 如果没有任何授权信息，则该方法必须返回空字符串。
     *
     * URI的授权格式是：[user-info@]host[:port]
     *
     * 如果端口组件没有设置或者是当前方案的标准端口，则不应该包括它。
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string URI授权信息的格式[user-info@]host[:port].
     */
    public function getAuthority() : string;

    /**
     * 获取URI的用户信息组件。
     *
     * 如果没有用户信息，则该方法必须返回空字符串。
     *
     * 如果用户存在于URI中，则该值将返回该值;此外，如果密码也存在，它将被附加到用户值，并使用冒号(“:”)分隔值。
     *
     * 尾随的“@”字符不是用户信息的一部分，不能添加。
     *
     * @return string 用户信息的格式："username[:password]"
     */
    public function getUserInfo() : string;

    /**
     * 获取URI的HOST组件。
     *
     * 如果没有HOST，则该方法必须返回空字符串。
     *
     * 返回的值必须被规范化为小写，遵守RFC3986节3.2.2。
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string URI HOST.
     */
    public function getHost() : string;

    /**
     * 获取URI的端口组件。
     *
     * 如果一个端口存在，并且它是非标准的当前方案，这个方法必须返回它为一个整数。如果端口是当前方案使用的标准端口，则该方法应该返回null。
     *
     * 如果不存在端口，且不存在scheme，则该方法必须返回null。
     * 如果没有端口，但是存在一个scheme，该方法可以返回该方案的标准端口，但是应该返回null。
     *
     * @return null|int
     */
    public function getPort() : ?int;

    /**
     * 获取URI的路径组件。
     *
     * 路径可以是空的，也可以是绝对的(以'/'开头)或无根路径(不是以'/'开始)。实现必须支持所有三个语法。
     *
     * 通常，空路径“”和绝对路径“/”被认为与RFC7230第2.7.3节中定义的一样。但是，这种方法不能自动执行这种标准化，因为在带有修剪的基本路径的上下文环境中，例如前端控制器，这种差异就变得很重要了。这是用户处理“”和“/”的任务。
     *
     * 返回的值必须是百分比编码的，但不能对任何字符进行双重编码。要确定编码的字符，请参考RFC3986，第2节和第3.3节。
     *
     * 例如，如果值应该包含一个斜杠(“/”)，而不是作为路径段之间的分隔符，那么该值必须以编码的形式(例如，“%2F”)传递给实例。
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string URI path.
     */
    public function getPath() : string;

    /**
     * 获取URI的查询[query]字符串。
     *
     * 如果没有查询字符串，则该方法必须返回空字符串。
     *
     * 引导的“?”字符不是查询的一部分，不能添加。
     *
     * 返回的值必须是百分比编码的，但不能对任何字符进行双重编码。要确定编码的字符，请参考RFC 3986，第2节和第3.4节。
     *
     * 例如，如果查询字符串的键/值对中的值应该包含一个&(“&”)，而不是作为值之间的分隔符，那么该值必须以编码的形式(例如，“%26”)传递给实例。
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string URI query 字符串.
     */
    public function getQuery() : string;

    /**
     * 获取URI的片段组件。
     *
     * 如果没有分段，则该方法必须返回空字符串。
     *
     * 前面的“#”字符不是片段的一部分，不能添加。
     *
     * 返回的值必须是百分比编码的，但不能对任何字符进行双重编码。要确定编码的字符，请参考RFC 3986，第2节和第3.5节。
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string URI fragment.
     */
    public function getFragment() : string;

    /**
     * 以指定的Scheme返回一个实例。
     *
     * 此方法必须保留当前实例的状态，并返回包含指定Scheme的实例。
     *
     * 实现必须是不区分大小写的支持方案“http”和“https”，如果需要，还可以提供其他方案。
     *
     * 一个空的scheme相当于取消这个scheme。
     *
     * @param string $scheme 与新实例一起使用的scheme。
     * @return static 一个具有指定scheme的新实例。
     * @throws \InvalidArgumentException 无效或不支持的scheme
     */
    public function withScheme(string $scheme) : self;

    /**
     * 返回一个具有指定用户信息的实例。
     *
     * 此方法必须保留当前实例的状态，并返回包含指定用户信息的实例。
     *
     * 密码是可选的，但用户信息必须包括用户;用户的空字符串相当于删除用户信息。
     *
     * @param string $user 用于授权的用户名
     * @param null|string $password 与$user关联的密码。.
     * @return static
     */
    public function withUserInfo(string $user, string $password = null) : self;

    /**
     * 返回指定HOST的实例。
     *
     * 此方法必须保留当前实例的状态，并返回包含指定HOST的实例。
     *
     * 空主机值等同于删除HOST。
     *
     * @param string $host 与新实例一起使用的HOST名称
     * @return static
     * @throws \InvalidArgumentException 无效的主机名
     */
    public function withHost(string $host) : self;

    /**
     * 返回一个具有指定端口的实例。
     *
     * 此方法必须保留当前实例的状态，并返回包含指定端口的实例。
     *
     * 实现必须为在已建立的TCP和UDP端口范围之外的端口增加一个异常。
     *
     * 端口等于空值等同于删除端口信息。
     *
     * @param null|int $port 与新实例一起使用的端口;空值删除端口信息。
     * @return static
     * @throws \InvalidArgumentException 无效的端口
     */
    public function withPort(?int $port) : self;

    /**
     * 返回一个具有指定路径的实例。
     *
     * 此方法必须保留当前实例的状态，并返回包含指定路径的实例。
     *
     * 路径可以是空的""，也可以是绝对的(以斜杠"/"开头)或无根(不是以斜杠"/"开始)。实现必须支持所有三个语法。
     *
     * 如果路径的目标是域相对而不是路径相对，那么它必须以一个斜线(“/”)开始。不以斜杠(“/”)开头的路径被假定为相对于应用程序或使用者已知的一些基本路径。
     *
     * 用户可以提供编码和解码的路径字符。实现确保在getPath()中列出的正确编码。
     *
     * @param string $path
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withPath(string $path) : self;

    /**
     * 返回一个具有指定[query]查询字符串的实例。
     *
     * 此方法必须保留当前实例的状态，并返回包含指定查询字符串的实例。
     *
     * 用户可以提供编码和解码的查询字符。实现确保在getQuery()中列出的正确编码。
     *
     * 空查询字符串值等同于删除查询字符串。
     *
     * @param string $query 与新实例一起使用的查询字符串。
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withQuery(string $query) : self;

    /**
     * 返回具有指定URI片段的实例。
     *
     * 此方法必须保留当前实例的状态，并返回包含指定URI片段的实例。
     *
     * 用户可以提供编码和解码的片段字符。实现确保了getFragment()中所描述的正确编码。
     *
     * 空的片段值等同于删除片段。
     *
     * @param string $fragment 与新实例一起使用的片段。
     * @return static 带有指定片段的新实例。
     */
    public function withFragment(string $fragment) : self;

    /**
     * 将字符串表示形式返回为URI引用。
     *
     * 根据当前URI的哪些组件，得到的字符串不是完整的URI，就是根据RFC3986，第4.1节所述的相对引用。该方法使用适当的分隔符将URI的各个组件连接起来:
     * - 如果有一个scheme，它必须是“:”的后缀
     * - 如果有授权信息，就必须以“//”为前缀
     * - 该路径可以连接，没有分隔符。但是，有两种情况需要调整路径，以使URI引用有效，因为PHP不允许在__toString()中抛出异常:
     *     - 如果路径是无根的，并且有一个授权信息存在，那么路径必须被“/”前缀
     *     - 如果路径以多于一个“/”开始，并且没有任何权限，那么开始的斜杠必须减少为1个。
     * - 如果存在查询[query string]，则必须以“?”为前缀
     * - 如果一个片段出现，它必须被“#”前缀
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string
     */
    public function __toString();
}
