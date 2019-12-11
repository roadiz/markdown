<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown;

use Symfony\Component\Stopwatch\Stopwatch;

if (!class_exists('\Parsedown')) {
    trigger_error(
        'Load Parsedown library before using Parsedown wrapper: composer require erusev/parsedown, composer require erusev/parsedown-extra',
        E_USER_WARNING
    );
}
/**
 * Class Parsedown
 *
 * @package RZ\Roadiz\Markdown
 */
final class Parsedown implements MarkdownInterface
{
    /**
     * @var Stopwatch|null
     */
    protected $stopwatch;
    /**
     * @var \Parsedown
     */
    private $instance;
    /**
     * @var \ParsedownExtra
     */
    private $instanceExtra;

    /**
     * Parsedown constructor.
     *
     * @param Stopwatch|null $stopwatch
     */
    public function __construct(?Stopwatch $stopwatch = null)
    {
        $this->instance = \Parsedown::instance();
        $this->instanceExtra = \ParsedownExtra::instance();
        $this->stopwatch = $stopwatch;
    }

    public function text(string $markdown = null): string
    {
        if (null !== $this->stopwatch) {
            $this->stopwatch->start(Parsedown::class . '::text');
        }
        $html = $this->instance->text($markdown);
        if (null !== $this->stopwatch) {
            $this->stopwatch->stop(Parsedown::class . '::text');
        }
        return $html;
    }

    /**
     * @param string|null $markdown
     *
     * @return string
     * @deprecated ParsedownExtra lib has not been updated for PHP 7.4
     */
    public function textExtra(string $markdown = null): string
    {
        if (null !== $this->stopwatch) {
            $this->stopwatch->start(Parsedown::class . '::textExtra');
        }
        $html = $this->instanceExtra->text($markdown);
        if (null !== $this->stopwatch) {
            $this->stopwatch->stop(Parsedown::class . '::textExtra');
        }
        return $html;
    }

    /**
     * @param string|null $markdown
     *
     * @return string
     */
    public function line(string $markdown = null): string
    {
        if (null !== $this->stopwatch) {
            $this->stopwatch->start(Parsedown::class . '::line');
        }
        $html = $this->instance->line($markdown);
        if (null !== $this->stopwatch) {
            $this->stopwatch->stop(Parsedown::class . '::line');
        }
        return $html;
    }
}
