<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown;

interface MarkdownInterface
{
    public function text(string $markdown = null): string;

    public function textExtra(string $markdown = null): string;

    public function line(string $markdown = null): string;
}
