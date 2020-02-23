<?php

namespace Repack\HtmlSanitizer\Node;

abstract class AbstractString
{
    /**
     * @var array<string, string>
     */
    private static $replacements = array(
        // Some DB engines will transform UTF8 full-width characters their classical version
        // if the data is saved in a non-UTF8 field
        '＜' => '&#xFF1C;',
        '＞' => '&#xFF1E;',
        '＋' => '&#xFF0B;',
        '＝' => '&#xFF1D;',
        '＠' => '&#xFF20;',
        '｀' => '&#xFF40;',
    );

    /**
     * @param string $string
     *
     * @return string
     */
    public function encodeHtmlEntities($string)
    {
        $subs = defined('ENT_SUBSTITUTE') ? ENT_SUBSTITUTE : ENT_QUOTES;

        $string = \htmlspecialchars($string, $subs, 'UTF-8');

        // $string = \htmlentities($string, ENT_NOQUOTES, 'UTF-8');

        $string = \str_replace(\array_keys(self::$replacements), \array_values(self::$replacements), $string);

        return $string;
    }
}
