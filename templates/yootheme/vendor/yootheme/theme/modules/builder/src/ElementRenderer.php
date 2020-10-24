<?php

namespace YOOtheme\Theme;

use YOOtheme\Util\Arr;

class ElementRenderer
{
    protected $theme;

    /**
     * Constructor.
     *
     * @param Theme $theme
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Builder renderer callback.
     *
     * @param  BuilderElement $element
     * @param  Collection     $type
     * @param  callable       $next
     * @return string
     */
    public function __invoke($element, $type, $next)
    {
        static $id, $prefix;

        if ($element->prefix) {
            $id = 0; $prefix = $element->prefix;
        }

        // ID
        $element->id = $prefix.$id++;

        // Defaults
        if ($defaults = $type['config.defaults']) {
            $element->addProps($defaults);
        }

        // Default props
        if ($props = $type['default.props']) {

            $modified = Arr::some($props, function ($value, $key) use ($element) {
                return isset($element[$key]);
            });

            if (!$modified) {
                $element->addProps($props);
            }
        }

        // Default children
        if ($children = $type['default.children'] and !count($element)) {
            $element->addChildren($children);
        }

        // Attributes
        if (!$element['attrs']) {
            $element['attrs'] = [];
        }

        // Animation
        if ($element['animation'] != 'none' && $element->parent('section', 'animation') && $element->parent->type == 'column') {
            $element['attrs.uk-scrollspy-class'] = $element['animation'] ? "uk-animation-{$element['animation']}" : true;
        }

        $class = (array) $element['class'] ?: [];

        // Visibility
        if ($visibility = $element['visibility']) {
            $class[] = "uk-visible@{$visibility}";
        }

        // Margin
        if ($element->type != 'row') {
            switch ($element['margin']) {
                case '':
                    break;
                case 'default':
                    $class[] = 'uk-margin';
                    break;
                default:
                    $class[] = "uk-margin-{$element['margin']}";
            }
        }

        if ($element['margin'] != 'remove-vertical') {
            if ($element['margin_remove_top']) {
                $class[] = 'uk-margin-remove-top';
            }
            if ($element['margin_remove_bottom']) {
                $class[] = 'uk-margin-remove-bottom';
            }
        }

        // Max Width
        if ($maxwidth = $element['maxwidth']) {

            $class[] = "uk-width-{$maxwidth}";

            switch ($element['maxwidth_align']) {
                case 'right':
                    $class[] = "uk-margin-auto-left";
                    break;
                case 'center':
                    $class[] = "uk-margin-auto";
                    break;
            }
        }

        // Text alignment
        if ($element['text_align'] && $element['text_align'] != 'justify' && $element['text_align_breakpoint']) {
            $class[] = "uk-text-{$element['text_align']}@{$element['text_align_breakpoint']}";
            if ($element['text_align_fallback']) {
                $class[] = "uk-text-{$element['text_align_fallback']}";
            }
        } else if ($element['text_align']) {
            $class[] = "uk-text-{$element['text_align']}";
        }

        // Custom CSS
        if ($element['css']) {

            if (!$element['id']) {
                $element['id'] = $element->id;
            }

            $pre = str_replace('#', '\#', $element['id']);
            $css = $this->theme->get('css', '');
            $css .= self::prefix("{$element['css']}\n", "#{$pre}");

            $this->theme->set('css', $css);
        }

        $element['class'] = $class;

        return $next($element, $type);
    }

    /**
     * Prefix CSS classes.
     *
     * @param  string $css
     * @param  string $prefix
     * @return string
     */
    protected static function prefix($css, $prefix = '')
    {
        $pattern = '/([@#:\.\w\[\]][@#:,>~="\'\+\-\.\(\)\w\s\[\]\*]*)({(?:[^{}]+|(?R))*})/s';

        if (preg_match_all($pattern, $css, $matches, PREG_SET_ORDER)) {

            $keys = [];

            foreach ($matches as $match) {

                list($match, $selector, $content) = $match;

                if (in_array($key = sha1($match), $keys)) {
                    continue;
                }

                if ($selector[0] != '@') {
                    $selector = preg_replace('/[^\n\,]+/', "{$prefix} $0", $selector);
                    $selector = preg_replace('/\.el-(element|section|column)/', '', $selector);
                }

                $css = str_replace($match, $selector.self::prefix($content, $prefix), $css); $keys[] = $key;
            }
        }

        return $css;
    }
}
