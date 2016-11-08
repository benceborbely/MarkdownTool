<?php

namespace Bence\MarkdownTool\Parser;

/**
 * Class Parser
 *
 * @author Bence Borbély
 */
class Parser
{
    /**
     * @param string $text
     * @return string
     */
    public function parse($text)
    {
        if (preg_match_all("/(?<!!)\[[^[\]]*\]\([^(\)]*\)/", $text, $matches)) {
            foreach ($matches[0] as $match) {
                $tokens = explode("](", $match);
                $truncatedHref = ltrim($tokens[0], "[");
                $truncatedText = rtrim($tokens[1], ")");
                $html = "<a href=\"" . $truncatedHref . "\">" . $truncatedText . "</a>";
                $text = str_replace($match, $html, $text);
            }
        }

        preg_match_all("/<a href=\".*[^ ]\">.*<\/a>/", $text, $matches);
        $links = [];
        foreach ($matches[0] as $match) {
            $hash = md5($match);
            $links[md5($match)] = $match;
            $text = str_replace($match, $hash, $text);
        }

        if (preg_match_all("/!\[[^[\]]*\]\([^(\)]*\)/", $text, $matches)) {
            foreach ($matches[0] as $match) {
                $tokens = explode("](", $match);
                $truncatedSrc = ltrim($tokens[0], "![");
                $truncatedAlt = rtrim($tokens[1], ")");
                $html = "<img src=\"" . $truncatedSrc . "\" alt=\"" . $truncatedAlt . "\" />";
                $text = str_replace($match, $html, $text);
            }
        }

        preg_match_all("/<img src=\".*[^ ]\" alt=\".*[^ ]\" \/>/", $text, $matches);
        $images = [];
        foreach ($matches[0] as $match) {
            $hash = md5($match);
            $images[md5($match)] = $match;
            $text = str_replace($match, $hash, $text);
        }

        if (preg_match_all("/_[^_]*_/", $text, $matches)) {
            foreach ($matches[0] as $match) {
                $truncated = trim($match, "_");
                $html = "<i>" . $truncated . "</i>";
                $text = str_replace($match, $html, $text);
            }
        }

        if (preg_match_all("/\'[^']*\'/", $text, $matches)) {
            foreach ($matches[0] as $match) {
                $truncated = trim($match, "'");
                $html = "<pre>" . $truncated . "</pre>";
                $text = str_replace($match, $html, $text);
            }
        }

        if (preg_match_all("/\*\*[^**]*\*\*/", $text, $matches)) {
            foreach ($matches[0] as $match) {
                $truncated = trim($match, "**");
                $html = "<strong>" . $truncated . "</strong>";
                $text = str_replace($match, $html, $text);
            }
        }

        foreach ($images as $hash => $html) {
            $text = str_replace($hash, $html, $text);
        }

        foreach ($links as $hash => $html) {
            $text = str_replace($hash, $html, $text);
        }

        return $text;
    }
}
