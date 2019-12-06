<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown;

use League\CommonMark\CommonMarkConverter;

final class CommonMark implements MarkdownInterface
{
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
     * @param CommonMarkConverter $textExtraConverter
     * @param CommonMarkConverter $lineConverter
     */
    public function __construct(
        CommonMarkConverter $textConverter,
        CommonMarkConverter $textExtraConverter,
        CommonMarkConverter $lineConverter
    ) {
        $this->textConverter = $textConverter;
        $this->textExtraConverter = $textExtraConverter;
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
