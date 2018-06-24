<?php
namespace asbamboo\http\psr;

/**
 * 本接口遵守psr7规范[https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md]
 *
 * 表示通过HTTP请求上载的文件的值对象。
 *
 * 该接口的实例被认为是不可变的;所有可能更改状态的方法都必须实现，这样它们才能保留当前实例的内部状态，并返回包含已更改状态的实例。
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年3月19日
 */
interface PsrUploadedFileInterface
{
    /**
     * 获取表示上传文件的流。
     *
     * 该方法必须返回一个StreamInterface实例，表示已上传的文件。此方法的目的是允许利用原生PHP流功能来操作文件上传，比如stream_copy_to_stream()(尽管结果需要在原生PHP流包装器中进行修饰，以处理这些函数)。
     *
     * 如果之前调用了moveTo()方法，则该方法必须引发异常。
     *
     * @return PsrStreamInterface 上传文件的流表示。
     * @throws \RuntimeException 在没有可用流或可以创建流的情况下。
     */
    public function getStream() : PsrStreamInterface;

    /**
     * 将上传的文件移动到新的位置。
     *
     * 使用此方法可以替代move_uploaded_file()。该方法保证在SAPI和非SAPI环境中工作。实现必须确定它们所在的环境，并使用适当的方法(move_uploaded_file()、rename()或流操作)来执行操作。
     *
     * $targetPath可能是一条绝对路径，或者是相对路径。如果是相对路径，那么解析应该与PHP的rename()函数相同。
     *
     * 必须在完成时删除原始文件或流。
     *
     * 如果调用此方法不止一次，任何后续调用都必须引发异常。
     *
     * 当在SAPI环境中使用$_FILES时，当使用moveTo()、is_uploaded_file()和move_uploaded_file()来编写文件时，应该使用它来确保权限和上传状态得到正确的验证。
     *
     * 如果您希望迁移到流，请使用getStream()，因为SAPI操作不能保证写入流目的地。
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     * @param string $targetPath 移动上传文件的路径
     * @throws \InvalidArgumentException 如果指定的$targetPath无效
     * @throws \RuntimeException 在移动操作期间的任何错误，或对方法的第二个或后续调用。
     */
    public function moveTo(string $target_path) : bool;

    /**
     * 获取文件大小。
     *
     * 如果可用的话，实现应该返回存储在$_FILES数组中的“size”键中的值，因为PHP根据传输的实际大小来计算这个值。
     *
     * @return int|null 如果未知，文件大小为字节或null。
     */
    public function getSize() : ?int;

    /**
     * 获取与上传文件相关的错误。
     *
     * 返回值必须是PHP的UPLOAD_ERR_XXX常量之一。
     *
     * 如果文件成功上传，此方法必须返回UPLOAD_ERR_OK。
     *
     * 实现应该返回存储在$_FILES数组中的文件“error”键中的值。
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     * @return int PHP的UPLOAD_ERR_XXX常量之一。
     */
    public function getError() : int;

    /**
     * 获取客户端发送的文件名。
     *
     * 不要相信该方法返回的值。客户端可以发送恶意的文件名，意图破坏或侵入您的应用程序。
     *
     * 实现应该返回存储在$_FILES数组中的文件“name”键中的值。
     *
     * @return string|null 客户端发送的文件名或null(如果不存在)。
     */
    public function getClientFilename() : ?string;

    /**
     * 获取客户端发送的媒体类型。
     *
     * 不要相信该方法返回的值。客户端可以发送恶意的媒体类型，意图破坏或攻击您的应用程序。
     *
     * 实现应该将存储在$_FILES数组中的“type”键中的值返回。
     *
     * @return string|null 如果没有提供，则客户机发送的媒体类型或null。
     */
    public function getClientMediaType() : ?string;
}
