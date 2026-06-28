<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Minimal, dependency-free HTML sanitizer built on PHP's native DOM
 * extension.
 *
 * Uses a strict whitelist: anything not explicitly allowed is either
 * unwrapped (its text kept, the tag dropped) or — for dangerous tags —
 * removed together with its contents. All attributes are stripped.
 *
 * This is the security boundary for rich-text fields rendered raw
 * ({!! !!}) on the public site, so it MUST run before storage.
 */
class HtmlSanitizer
{
    /**
     * Tags whose tag is kept. Everything else is unwrapped.
     */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u',
        'ul', 'ol', 'li', 'span',
    ];

    /**
     * Tags removed entirely along with their contents (never unwrapped,
     * because keeping their text could still leak a payload).
     */
    private const DANGEROUS_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'applet',
        'link', 'meta', 'base', 'form', 'input', 'button', 'select',
        'textarea', 'noscript', 'svg', 'math', 'template',
    ];

    /**
     * Sanitize an HTML fragment.
     */
    public function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $doc = new DOMDocument();

        // Wrap in a <div> root so top-level fragments (loose <p>, text)
        // parse predictably, and declare UTF-8 so multibyte content
        // survives the round-trip.
        libxml_use_internal_errors(true);
        $doc->loadHTML(
            '<?xml encoding="utf-8" ?><div>' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $root = $doc->getElementsByTagName('div')->item(0);

        if ($root) {
            $this->processNode($root);
        }

        // Emit only the wrapper's children (not the wrapper itself).
        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }

    /**
     * Recursively sanitize a node's subtree (depth-first).
     *
     * Children are processed before a parent is unwrapped, so moved-up
     * nodes are already clean.
     */
    private function processNode(DOMNode $node): void
    {
        if (! $node->hasChildNodes()) {
            return;
        }

        // Snapshot the child list; we mutate the tree while walking it.
        $children = iterator_to_array($node->childNodes);

        foreach ($children as $child) {
            // Drop comments / processing instructions; keep text nodes.
            if (! ($child instanceof DOMElement)) {
                if ($child->nodeType === XML_COMMENT_NODE
                    || $child->nodeType === XML_PI_NODE) {
                    $node->removeChild($child);
                }
                continue;
            }

            $tag = strtolower($child->nodeName);

            // Recurse first so descendants are clean before we unwrap.
            $this->processNode($child);

            if (in_array($tag, self::DANGEROUS_TAGS, true)) {
                $node->removeChild($child);
                continue;
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $this->unwrap($node, $child);
                continue;
            }

            // Allowed tag: keep it, but drop every attribute.
            $this->stripAttributes($child);
        }
    }

    /**
     * Replace an element with its own children (lift them into $parent),
     * then delete the now-empty element.
     */
    private function unwrap(DOMNode $parent, DOMElement $el): void
    {
        while ($el->firstChild) {
            $parent->insertBefore($el->firstChild, $el);
        }

        $parent->removeChild($el);
    }

    /**
     * Remove all attributes from an element.
     */
    private function stripAttributes(DOMElement $el): void
    {
        while ($el->hasAttributes()) {
            $el->removeAttribute($el->attributes->item(0)->nodeName);
        }
    }
}
