<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown;

use League\CommonMark\CommonMarkConverter;
use Symfony\Component\Stopwatch\Stopwatch;

final class CommonMark implements MarkdownInterface
{
    /**
     * @var Stopwatch|null
     */
    protected $stopwatch;
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
     * @param Stopwatch|null      $stopwatch
     */
    public function __construct(
        CommonMarkConverter $textConverter,
        CommonMarkConverter $textExtraConverter,
        CommonMarkConverter $lineConverter,
        ?Stopwatch $stopwatch = null
    ) {
        $this->textConverter = $textConverter;
        $this->textExtraConverter = $textExtraConverter;
        $this->lineConverter = $lineConverter;
        $this->stopwatch = $stopwatch;
    }

    public function text(string $markdown = null): string
    {
        if (null === $markdown) {
            return '';
        }
        if (null !== $this->stopwatch) {
            $this->stopwatch->start(CommonMark::class . '::text');
        }
        $html = $this->textConverter->convertToHtml($markdown);
        if (null !== $this->stopwatch) {
            $this->stopwatch->stop(CommonMark::class . '::text');
        }
        return $html;
    }

    public function textExtra(string $markdown = null): string
    {
        if (null === $markdown) {
            return '';
        }
        if (null !== $this->stopwatch) {
            $this->stopwatch->start(CommonMark::class . '::textExtra');
        }
        $html = $this->textExtraConverter->convertToHtml($markdown);
        if (null !== $this->stopwatch) {
            $this->stopwatch->stop(CommonMark::class . '::textExtra');
        }
        return $html;
    }

    public function line(string $markdown = null): string
    {
        if (null === $markdown) {
            return '';
        }
        if (null !== $this->stopwatch) {
            $this->stopwatch->start(CommonMark::class . '::line');
        }
        $html = $this->lineConverter->convertToHtml($markdown);
        if (null !== $this->stopwatch) {
            $this->stopwatch->stop(CommonMark::class . '::line');
        }
        return $html;
    }
}
