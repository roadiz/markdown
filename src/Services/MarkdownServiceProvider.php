<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown\Services;

use Doctrine\Common\Collections\ArrayCollection;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use RZ\CommonMark\Ext\Footnote\FootnoteExtension;
use RZ\Roadiz\Markdown\CommonMark;
use RZ\Roadiz\Markdown\MarkdownInterface;
use RZ\Roadiz\Markdown\Twig\MarkdownExtension;

final class MarkdownServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[MarkdownInterface::class] = function (Container $c) {
            return new CommonMark(
                $c['commonmark.text_converter'],
                $c['commonmark.text_extra_converter'],
                $c['commonmark.line_converter'],
                $c->offsetExists('stopwatch') ? $c['stopwatch'] : null
            );
        };

        $container[Environment::class] = $container->factory(function (Container $c) {
            return Environment::createCommonMarkEnvironment();
        });

        $container['commonmark.text_converter.environment'] = function (Container $c) {
            $environment = $c[Environment::class];
            $environment->addExtension(new AutolinkExtension());
            $environment->addExtension(new StrikethroughExtension());
            $environment->addExtension(new TableExtension());
            return $environment;
        };
        $container['commonmark.text_converter.config'] = function () {
            return [
                'html_input' => 'allow'
            ];
        };
        $container['commonmark.text_converter'] = function (Container $c) {
            $environment = $c[Environment::class];
            $environment->addExtension(new TableExtension());

            return new CommonMarkConverter(
                $c['commonmark.text_converter.config'],
                $c['commonmark.text_converter.environment']
            );
        };

        $container['commonmark.text_extra_converter.environment'] = function (Container $c) {
            $extraEnvironment = $c[Environment::class];
            $extraEnvironment->addExtension(new AutolinkExtension());
            $extraEnvironment->addExtension(new SmartPunctExtension());
            $extraEnvironment->addExtension(new StrikethroughExtension());
            $extraEnvironment->addExtension(new TableExtension());
            $extraEnvironment->addExtension(new TaskListExtension());
            $extraEnvironment->addExtension(new FootnoteExtension());
            return $extraEnvironment;
        };
        $container['commonmark.text_extra_converter.config'] = function () {
            return [
                'html_input' => 'allow'
            ];
        };
        $container['commonmark.text_extra_converter'] = function (Container $c) {
            return new CommonMarkConverter(
                $c['commonmark.text_extra_converter.config'],
                $c['commonmark.text_extra_converter.environment']
            );
        };

        $container['commonmark.line_converter.environment'] = function (Container $c) {
            $lineEnvironment = new Environment();
            $lineEnvironment->addExtension(new StrikethroughExtension());
            $lineEnvironment->addExtension(new InlinesOnlyExtension());
            return $lineEnvironment;
        };

        $container['commonmark.line_converter.config'] = function () {
            return [
                'html_input' => 'escape'
            ];
        };

        $container['commonmark.line_converter'] = function (Container $c) {
            return new CommonMarkConverter(
                $c['commonmark.line_converter.config'],
                $c['commonmark.line_converter.environment']
            );
        };

        if ($container->offsetExists('twig.extensions')) {
            $container->extend('twig.extensions', function (ArrayCollection $extensions, Container $c) {
                $extensions->add(new MarkdownExtension($c[MarkdownInterface::class]));
                return $extensions;
            });
        }
    }
}
