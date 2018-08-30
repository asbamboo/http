<?php
namespace asbamboo\http\psr;

/**
 * 本接口遵守psr7规范[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md]
 *
 * 描述数据流
 *
 * 通常，一个实例将包装一个PHP流;这个接口为最常见的操作提供了一个包装器，包括将整个流序列化到一个字符串。
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月17日
 */
interface PsrStreamInterface
{
    /**
     * 从开始到结束，从流中读取所有数据。
     *
     * 此方法必须尝试在读取数据之前查找流的开始，并读取流，直到到达结束为止。
     *
     * 警告:这可能试图将大量数据加载到内存中。
     *
     * 此方法不应引发异常，以符合PHP的字符串转换操作。
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString() : string;

    /**
     * 关闭流和任何基础资源
     *
     * @return void
     */
    public function close() : void;

    /**
     * 将任何基础资源从流中分离出来。
     *
     * 在流被分离后，流处于不可使用的状态。
     *
     * @return resource|null 潜在的PHP流或者null
     */
    public function detach() /*: ?resource*/;

    /**
     * 返回获取流的大小或者null。
     *
     * @return int|null 如果已知，则返回字节大小，如果未知，则返回null。
     */
    public function getSize() : ?int;

    /**
     * 返回文件读/写指针的当前位置。
     *
     * @return int 文件指针的位置。
     * @throws \RuntimeException.
     */
    public function tell() : int;

    /**
     * 如果流位于流的末尾，则返回true。
     *
     * @return bool
     */
    public function eof() : bool;

    /**
     * 返回是否可以看到流。
     *
     * @return bool
     */
    public function isSeekable() : bool;

    /**
     * 从流的某个位置[position]开始读取
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     *
     * @param int $offset 流偏移位置 Stream offset
     * @param int $whence 指定如何根据查找偏移量计算游标位置。有效值与内置的PHP “fseek()”的值相同。SEEK_SET:设置位置等于偏移字节: SEEK_CUR设置位置到当前位置加上偏移量， SEEK_END:设置位置到流尾加偏移量。
     *
     * @return int 成功则返回 0；否则返回 -1。移动到EOF之后的位置不算错误。
     *
     * @throws \RuntimeException on failure.
     */
    public function seek(int $offset, int $whence = SEEK_SET) : int;

    /**
     * 倒回流的开头。
     *
     * 如果流不可见，该方法将引发异常
     * 否则，它将执行seek(0)
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @return bool
     * @throws \RuntimeException
     */
    public function rewind() : bool;

    /**
     * 返回流是否可写。
     *
     * @return bool
     */
    public function isWritable() : bool;

    /**
     * 将数据写入流。
     *
     * @param string $string 要写的字符串
     * @return int Returns 写入流的字节数
     *
     * @throws \RuntimeException.
     */
    public function write(string $string) : int;

    /**
     * 返回流是否可读.
     *
     * @return bool
     */
    public function isReadable() : bool;

    /**
     * 从流中读取数据
     *
     * @param int $length 从对象中读取$length字节并返回它们。如果底层流调用返回的字节数更少，则可以返回少于$length字节。
     * @return string 返回从流中读取的数据，如果没有可用的字节，则返回空字符串。
     * @throws \RuntimeException.
     */
    public function read(int $length) : string;

    /**
     * 返回字符串中的其余内容
     *
     * @return string
     * @throws \RuntimeException 如果无法读取或在读取时发生错误。
     */
    public function getContents() : string;

    /**
     * 获取流元数据作为关联数组或检索特定键。
     *
     * 返回的键与PHP的stream_get_meta_data()函数返回的键相同。
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key 特定的元数据检索
     *
     * @return array|mixed|null 如果没有提供键，返回一个关联数组。如果提供了密钥，则返回一个特定的键值，如果没有找到该值，则返回值。
     */
    public function getMetadata(string $key = null);
}
