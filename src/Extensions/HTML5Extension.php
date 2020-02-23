<?php

namespace Repack\HtmlSanitizer\Extensions;

use Repack\HtmlSanitizer\AspectExtension;
use Repack\HtmlSanitizer\NodeVisitor\TagNodeVisitor;

class HTML5Extension implements AspectExtension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'html5';
    }

    /**
     * @param array $config
     *
     * @return AspectNodeVisitor[]
     */
    public function createNodeVisitors(array $config = array())
    {
        $globalOptions = array(
            'allowed_attributes', 'allowed_classes',
            'blocked_attributes', 'blocked_classes',
            'childless', 'convert_elements',
        );

        $tagConfigs = $this->getDefaultConfig();

        foreach ($tagConfigs as $tag => $tagConfig) {
            //
            if ((isset($config['tags_allowed']) && !in_array($tag, $config['tags_allowed'])) ||
                (isset($config['tags_disallowed']) && in_array($tag, $config['tags_disallowed']))) {
                unset($tagConfigs[$tag]);
                continue;
            }

            // global options
            foreach ($globalOptions as $option) {
                if (isset($config[$option])) {
                    $tagConfig[$option] = array_unique(array_merge(
                        isset($tagConfig[$option]) ? $tagConfig[$option] : array(), $config[$option]
                    ));
                }
            }

            $tagConfigs[$tag] = new TagNodeVisitor($tag, $tagConfig);
        }

        return $tagConfigs;
    }

    /**
     * @return array
     */
    public function getDefaultConfig()
    {
        return array(
            // Main root
            'html' => array(),

            // Document metadata
            'base' => array('childless' => true),
            'head' => array(),
            'link' => array('childless' => true),
            'meta' => array('childless' => true),
            'style' => array(),
            'title' => array(),

            // Sectioning root
            'body' => array(),

            // Content sectioning
            'address' => array(),
            'article' => array(),
            'aside' => array(),
            'footer' => array(),
            'header' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'hgroup' => array(),
            'main' => array(),
            'nav' => array(),
            'section' => array(),

            // Text content
            'blockquote' => array(),
            'dd' => array(),
            'dir' => array(),
            'div' => array(),
            'dl' => array(),
            'dt' => array(),
            'figcaption' => array(),
            'figure' => array(),
            'hr' => array('childless' => true),
            'li' => array(),
            'ol' => array(),
            'ul' => array(),
            'p' => array(),
            'pre' => array(),

            // Inline text semantics
            'a' => array(),
            'abbr' => array(),
            'b' => array(),
            'bdi' => array(),
            'bdo' => array(),
            'br' => array('childless' => true),
            'cite' => array(),
            'code' => array(),
            'data' => array(),
            'dfn' => array(),
            'em' => array(),
            'i' => array(),
            'kbd' => array(),
            'mark' => array(),
            'q' => array(),
            'rb' => array(),
            'rp' => array(),
            'rt' => array(),
            'rtc' => array(),
            'ruby' => array(),
            's' => array(),
            'samp' => array(),
            'small' => array(),
            'span' => array(),
            'strong' => array(),
            'sub' => array(),
            'sup' => array(),
            'time' => array(),
            'tt' => array(),
            'u' => array(),
            'var' => array(),
            'wbr' => array('childless' => true),

            // Image and multimedia
            'area' => array('childless' => true),
            'audio' => array(),
            'img' => array('childless' => true),
            'map' => array(),
            'track' => array('childless' => true),
            'video' => array(),

            // Embedded content
            'applet' => array(),
            'embed' => array(),
            'iframe' => array(),
            'noembed' => array(),
            'object' => array(),
            'param' => array('childless' => true),
            'picture' => array(),
            'source' => array(),

            // Scripting
            'canvas' => array(),
            'noscript' => array(),
            'script' => array(),

            // Demarcating edits
            'del' => array(),
            'ins' => array(),

            // Table content
            'caption' => array(),
            'col' => array('childless' => true),
            'colgroup' => array(),
            'table' => array(),
            'tbody' => array(),
            'td' => array(),
            'tfoot' => array(),
            'th' => array(),
            'thead' => array(),
            'tr' => array(),

            // Forms
            'button' => array(),
            'datalist' => array(),
            'fieldset' => array(),
            'form' => array(),
            'input' => array('childless' => true),
            'label' => array(),
            'legend' => array(),
            'meter' => array(),
            'optgroup' => array(),
            'option' => array(),
            'output' => array(),
            'progress' => array(),
            'select' => array(),
            'textarea' => array(),

            // Interactive elements
            'details' => array(),
            'dialog' => array(),
            'menu' => array(),
            'menuitem' => array(),
            'summary' => array(),

            // Web Components
            'content' => array(),
            'element' => array(),
            'shadow' => array(),
            'slot' => array(),
            'template' => array(),
        );
    }
}
