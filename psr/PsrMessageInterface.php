<?php
namespace asbamboo\http\psr;

/**
 * 本接口遵守psr7规范[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md]
 *
 * HTTP消息由客户端到服务器和响应的请求组成
 * 从服务器到客户端 该接口定义了常见的方法
 *
 * 消息被认为是不可变的;
 * 所有可能改变状态的方法都必须被执行，这样他们就保留了当前的内部状态消息并返回包含已更改状态的实例。
 *
 * @link http://www.ietf.org/rfc/rfc7230.txt
 * @link http://www.ietf.org/rfc/rfc7231.txt
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
interface PsrMessageInterface
{
    /**
     * 以字符串形式检索HTTP协议版本
     * 该方法返回字符串应该只包含HTTP版本号 (例如: "1.1", "1.0").
     *
     * @return string
     */
    public function getProtocolVersion() : string;

    /**
     * 返回一个具有指定HTTP协议版本的实例
     * 参数$version必须只包含HTTP版本号 (例如: "1.1", "1.0").
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性
     * 并且必须返回一个具有新的协议版本的实例
     *
     * @param string $version
     * @return static
     */
    public function withProtocolVersion(string $version) : self;

    /**
     *
     * 检索所有消息头值
     * 这些键表示将通过信号发送的消息头名称，每个值都是与header关联的字符串数组。
     * 该方法返回消息头的关联数组。key必须是一个头名，每个值必须是该头的字符串数组。
     *
     * @example 以字符串的形式表示头
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     * @example 转换成消息头发出:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     * 虽然标题名称不区分大小写，但getheader()将保留最初指定标题的确切情况
     *
     * @return string[][]
     */
    public function getHeaders() : array;

    /**
     * 检查给定的大小写不敏感的名称是否存在标题。
     *
     * @param string $name 不区分大小写的头字段名
     * @return bool 如果任何标题名称使用不区分大小写的字符串比较来匹配给定的标题名称，则返回true。如果消息中没有找到匹配的头名，则返回false。
     */
    public function hasHeader(string $name) : bool;

    /**
     * 通过给定的不区分大小写的名称检索消息头值
     * 该方法返回给定的不区分大小写标题的所有头值的数组
     *
     * 如果消息头未出现在消息中，则该方法必须返回一个空数组
     *
     * @param string $name 不区分大小写的头字段名
     * @return string[] 为给定的头提供的一组字符串值。如果消息头未出现，则此方法必须返回空数组。
     */
    public function getHeader(string $name) : array;

    /**
     *为单个标头检索逗号分隔的值字符串。
     *
     *此方法返回给定的所有标头值。不区分大小写的头名称作为字符串永一个逗号","连接在一起使用。
     *
     *注意:不是所有的头值都可以适当地使用逗号连接表示。对于这样的头，使用getHeader()代替。在连接时提供您自己的分隔符。
     *
     *如果消息头未出现，则此方法必须返回一个空字符串。
     *
     * @param string $name 不区分大小写的头字段名。
     * @return string 为给定的头提供的一串值。用逗号连接在一起。如果头没有出现。消息，此方法必须返回空字符串。
     */
    public function getHeaderLine(string $name) : string;

    /**
     * 用提供的值替换指定的头返回一个实例。
     *
     * 虽然标题名称不区分大小写，但标题的大小写名称将会被原样保存，并由getHeaders()返回。
     *
     * 此方法必须以保留该方法的方式实现消息的不可变性，并且必须返回具有该消息的新的、或更新的标题和值实例。
     *
     * @param string $name 不区分大小写的头字段名。
     * @param string|string[] $value 标题的值(s)。
     *
     * @return static
     *
     * @throws \InvalidArgumentException 抛出无效的标题异常
     */
    public function withHeader(string $name, /*array|string*/ $value) : self;

    /**
     * 该方法将保留指定标题的现有值
     * 如果标题之前存在，新值将被追加到现有列表中。
     * 如果标题没有，它将被添加。、
     *
     * 此方法必须以保留该方法的方式实现消息的不可变性，并且必须返回具有该消息的新的、或更新的标题和值实例。
     *
     * @param string $name 不区分大小写的头字段名。
     * @param string|string[] $value 标题的值(s)。
     *
     * @return static
     *
     * @throws \InvalidArgumentException 抛出无效的标题异常
     */
    public function withAddedHeader(string $name, /*array|string*/ $value) : self;

    /**
     * 返回一个没有指定标题的实例。
     *
     * 头的名称不区分大小写。
     *
     * 应该以这样的方式实现此方法，以保持消息的不可变性，并且必须返回一个实例，该实例将删除已命名的消息头。
     *
     * @param string $name 删除不区分大小写的头字段名
     * @return static
     */
    public function withoutHeader(string $name) : self;

    /**
     * 获取消息的主体。
     *
     * @return PsrStreamInterface 以流的形式返回主体。
     */
    public function getBody() : PsrStreamInterface;

    /**
     * 返回一个具有指定消息体[body]的实例。
     *
     * 消息体[body]必须是一个StreamInterface对象。
     *
     * 必须以这种方式实现此方法，以保持消息的不可变性，并且必须返回具有新主体[body]流的新实例。
     *
     * @param PsrStreamInterface $body 消息体.
     * @return static
     * @throws \InvalidArgumentException 当消息体是无效的时抛出异常
     */
    public function withBody(PsrStreamInterface $body) : self;
}
