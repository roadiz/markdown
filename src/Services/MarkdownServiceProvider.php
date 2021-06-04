<?php
declare(strict_types=1);

namespace RZ\Roadiz\Markdown\Services;

use Doctrine\Common\Collections\ArrayCollection;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use RZ\Roadiz\Markdown\CommonMark;
use RZ\Roadiz\Markdown\MarkdownInterface;
use RZ\Roadiz\Markdown\Twig\MarkdownExtension;

final class MarkdownServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple): void
    {
        /*
         * $pimple[MarkdownInterface::class] = function (Container $c) {
         *     return new \RZ\Roadiz\Markdown\Parsedown(
         *         $c->offsetExists('stopwatch') ? $c['stopwatch'] : null
         *     );
         * };
         */
        $pimple[MarkdownInterface::class] = function (Container $c) {
            return new CommonMark(
                $c['commonmark.text_converter'],
                $c['commonmark.text_extra_converter'],
                $c['commonmark.line_converter'],
                $c->offsetExists('stopwatch') ? $c['stopwatch'] : null
            );
        };

        $pimple['commonmark.text_converter.config.default'] = function () {
            return [
                'external_link' => [
                    'open_in_new_window' => true,
                    'noopener' => 'external',
                    'noreferrer' => 'external',
                ]
            ];
        };

        $pimple['commonmark.text_converter.config'] = function (Container $c) {
            return array_merge($c['commonmark.text_converter.config.default'], [
                'html_input' => 'allow'
            ]);
        };

        /**
         * @param Container $c
         * @return ConfigurableEnvironmentInterface
         */
        $pimple['commonmark.text_converter.environment'] = function (Container $c) {
            $environment = Environment::createCommonMarkEnvironment();
            $environment->addExtension(new TableExtension());
            $environment->addExtension(new ExternalLinkExtension());
            $environment->mergeConfig($c['commonmark.text_converter.config']);
            return $environment;
        };

        /**
         * @param Container $c
         * @return MarkdownConverter
         */
        $pimple['commonmark.text_converter'] = function (Container $c) {
            return new MarkdownConverter(
                $c['commonmark.text_converter.environment']
            );
        };

        $pimple['commonmark.text_extra_converter.config'] = function (Container $c) {
            return array_merge($c['commonmark.text_converter.config.default'], [
                'html_input' => 'allow'
            ]);
        };

        /**
         * @param Container $c
         * @return ConfigurableEnvironmentInterface
         */
        $pimple['commonmark.text_extra_converter.environment'] = function (Container $c) {
            $extraEnvironment = Environment::createCommonMarkEnvironment();
            $extraEnvironment->addExtension(new AutolinkExtension());
            $extraEnvironment->addExtension(new ExternalLinkExtension());
            $extraEnvironment->addExtension(new SmartPunctExtension());
            $extraEnvironment->addExtension(new StrikethroughExtension());
            $extraEnvironment->addExtension(new TableExtension());
            $extraEnvironment->addExtension(new TaskListExtension());
            $extraEnvironment->addExtension(new FootnoteExtension());
            $extraEnvironment->mergeConfig($c['commonmark.text_extra_converter.config']);
            return $extraEnvironment;
        };

        /**
         * @param Container $c
         * @return MarkdownConverter
         */
        $pimple['commonmark.text_extra_converter'] = function (Container $c) {
            return new MarkdownConverter(
                $c['commonmark.text_extra_converter.environment']
            );
        };

        $pimple['commonmark.line_converter.config'] = function (Container $c) {
            return array_merge($c['commonmark.text_converter.config.default'], [
                'html_input' => 'escape'
            ]);
        };

        /**
         * @param Container $c
         * @return Environment
         */
        $pimple['commonmark.line_converter.environment'] = function (Container $c) {
            $lineEnvironment = new Environment();
            $lineEnvironment->addExtension(new InlinesOnlyExtension());
            $lineEnvironment->addExtension(new ExternalLinkExtension());
            $lineEnvironment->mergeConfig($c['commonmark.line_converter.config']);
            return $lineEnvironment;
        };

        /**
         * @param Container $c
         * @return MarkdownConverter
         */
        $pimple['commonmark.line_converter'] = function (Container $c) {
            return new MarkdownConverter(
                $c['commonmark.line_converter.environment']
            );
        };

        if ($pimple->offsetExists('twig.extensions')) {
            $pimple->extend('twig.extensions', function (ArrayCollection $extensions, Container $c) {
                $extensions->add(new MarkdownExtension($c[MarkdownInterface::class]));
                return $extensions;
            });
        }
    }
}
