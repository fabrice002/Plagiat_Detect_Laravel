<?php

namespace App\Helpers;

use Sunra\PhpSimple\HtmlDomParser;

class HtmlHelper
{
    public static function extractText($node)
    {
        if ($node->nodetype === HDOM_TYPE_TEXT) {
            return $node->innertext;
        } elseif ($node->nodetype === HDOM_TYPE_ELEMENT &&
            $node->tag !== 'script' &&
            $node->tag !== 'style') {
            $contents = "";
            foreach ($node->children as $child) {
                $text = self::extractText($child);
                if (!empty($text)) {
                    if (!empty($contents)) {
                        $contents .= " ";
                    }
                    $contents .= str_replace(":", ">", $text);
                }
            }
            return $contents;
        } else {
            return "";
        }
    }
}


/* "psr-4": {
    "App\\": "app/",
    "Database\\Factories\\": "database/factories/",
    "Database\\Seeders\\": "database/seeders/"
},
 */
