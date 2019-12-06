<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown;

use League\CommonMark\CommonMarkConverter;

final class CommonMark implements MarkdownInterface
{
    /**
     * @var CommonMarkConverter
     */
    protected $extraTextConverter;
    /**
     * @var CommonMarkConverter
     */
    private $textConverter;
    /**
     * @var CommonMarkConverter
     */
    private $lineConverter;
    /**
     * @var CommonMarkConverter
     */
    private $textExtraConverter;

    /**
     * CommonMark constructor.
     *
     * @param CommonMarkConverter $textConverter
     * @param CommonMarkConverter $extraTextConverter
     * @param CommonMarkConverter $lineConverter
     */
    public function __construct(
        CommonMarkConverter $textConverter,
        CommonMarkConverter $extraTextConverter,
        CommonMarkConverter $lineConverter
    ) {
        $this->textConverter = $textConverter;
        $this->extraTextConverter = $extraTextConverter;
        $this->lineConverter = $lineConverter;
    }

    public function text(string $markdown = null): string
    {
        if (null === $markdown) {
            return '';
        }
        return $this->textConverter->convertToHtml($markdown);
    }

    public function textExtra(string $markdown = null): string
    {
        if (null === $markdown) {
            return '';
        }
        return $this->textExtraConverter->convertToHtml($markdown);
    }

    public function line(string $markdown = null): string
    {
        if (null === $markdown) {
            return '';
        }
        return $this->lineConverter->convertToHtml($markdown);
    }
}
