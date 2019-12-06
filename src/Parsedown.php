<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown;

/**
 * Class Parsedown
 *
 * @package RZ\Roadiz\Markdown
 */
final class Parsedown implements MarkdownInterface
{
    /**
     * @var \ParsedownExtra
     */
    private $instance;
    /**
     * @var \ParsedownExtra
     */
    private $instanceExtra;

    /**
     * Parsedown constructor.
     */
    public function __construct()
    {
        $this->instance = \Parsedown::instance();
        $this->instanceExtra = \ParsedownExtra::instance();
    }

    public function text(string $markdown = null): string
    {
        return $this->instance->text($markdown);
    }

    /**
     * @param string|null $markdown
     *
     * @return string
     * @deprecated ParsedownExtra lib has not been updated for PHP 7.4
     */
    public function textExtra(string $markdown = null): string
    {
        return $this->instanceExtra->text($markdown);
    }

    public function line(string $markdown = null): string
    {
        return $this->instance->line($markdown);
    }
}
