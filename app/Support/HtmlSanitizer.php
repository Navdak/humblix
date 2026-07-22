<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'a', 'blockquote', 'br', 'code', 'em', 'h2', 'h3', 'h4', 'hr', 'li', 'ol', 'p', 'strong', 'ul',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'target', 'rel', 'title'],
    ];

    private const BLOCKED_TAGS = [
        'button', 'embed', 'form', 'iframe', 'input', 'math', 'object', 'script', 'style', 'svg',
    ];

    public static function clean(?string $html): string
    {
        $html = self::repairMojibake(trim((string) $html));

        if ($html === '') {
            return '';
        }

        if (! class_exists(DOMDocument::class)) {
            return strip_tags($html, '<a><blockquote><br><code><em><h2><h3><h4><hr><li><ol><p><strong><ul>');
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $document->loadHTML(
            '<!doctype html><html><head><meta charset="UTF-8"></head><body>'.$html.'</body></html>',
            LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//*') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($node->nodeName);

            if (in_array($tag, ['html', 'body'], true)) {
                continue;
            }

            if (in_array($tag, self::BLOCKED_TAGS, true)) {
                self::removeNode($node);
                continue;
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                self::unwrapNode($node);
                continue;
            }

            self::cleanAttributes($node, $tag);
        }

        $body = $document->getElementsByTagName('body')->item(0);
        $output = '';

        if ($body) {
            foreach ($body->childNodes as $child) {
                $output .= $document->saveHTML($child);
            }
        }

        return trim(self::repairMojibake($output));
    }

    private static function repairMojibake(string $value): string
    {
        return strtr($value, [
            "\u{00E2}\u{20AC}\u{201D}" => "\u{2014}",
            "\u{00E2}\u{20AC}\u{201C}" => "\u{2013}",
            "\u{00E2}\u{20AC}\u{02DC}" => "\u{2018}",
            "\u{00E2}\u{20AC}\u{2122}" => "\u{2019}",
            "\u{00E2}\u{20AC}\u{0153}" => "\u{201C}",
            "\u{00E2}\u{20AC}\u{009D}" => "\u{201D}",
            "\u{00E2}\u{20AC}\u{00A6}" => "\u{2026}",
            "\u{00E2}\u{20AC}\u{00A2}" => "\u{2022}",
            "\u{00C2}\u{00A9}" => "\u{00A9}",
            "\u{00C2}\u{00AE}" => "\u{00AE}",
            "\u{00C2}\u{00B0}" => "\u{00B0}",
            "\u{00C2}\u{00B7}" => "\u{00B7}",
            "\u{00C2}\u{00A0}" => " ",
            "\u{00C2}" => "",
        ]);
    }

    private static function cleanAttributes(DOMElement $node, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];

        for ($i = $node->attributes->length - 1; $i >= 0; $i--) {
            $attribute = $node->attributes->item($i);
            $name = strtolower($attribute->nodeName);
            $value = trim($attribute->nodeValue);

            if (! in_array($name, $allowed, true) || str_starts_with($name, 'on')) {
                $node->removeAttributeNode($attribute);
                continue;
            }

            if ($name === 'href' && ! self::safeUrl($value)) {
                $node->removeAttributeNode($attribute);
            }
        }

        if ($tag === 'a') {
            $node->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function safeUrl(string $url): bool
    {
        if (str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        return in_array(strtolower((string) $scheme), ['http', 'https', 'mailto', 'tel'], true);
    }

    private static function unwrapNode(DOMNode $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private static function removeNode(DOMNode $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        $parent->removeChild($node);
    }
}
