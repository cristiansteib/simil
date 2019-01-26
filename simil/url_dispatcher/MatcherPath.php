<?php

/**@example <id:integer>
 * @example <something:slug>
 * @example <name:string:>
 */

class MatcherPath extends Matcher {

    const RE_REPLACE_INTEGER_GROUP = "(?P<%id%>[0-9]+)";
    const RE_REPLACE_STRING_GROUP = "(?P<%id%>[a-zA-Z]+)";
    const RE_REPLACE_SLUG_GROUP = "(?P<%id%>.+)";

    const SEARCH_ID_TAGS_INTEGERS = "/<(?P<id>[a-zA-Z]+):integer>/";
    const SEARCH_ID_TAGS_STRING = "/<(?P<id>[a-zA-Z]+):string>/";
    const SEARCH_ID_TAGS_SLUG = "/<(?P<id>[a-zA-Z]+):slug>/";


    function __construct($url_lazy) {
        parent::__construct($url_lazy);
        $this->generate_integers_re();
        $this->generate_strings_re();
        $this->generate_slug_re();
    }

    private function generate_strings_re() {
        $this->match_and_replace_to_real_regex(
            $this->url_pattern,
            self::SEARCH_ID_TAGS_STRING,
            self::RE_REPLACE_STRING_GROUP
        );
    }

    private function generate_slug_re() {
        $this->match_and_replace_to_real_regex(
            $this->url_pattern,
            self::SEARCH_ID_TAGS_SLUG,
            self::RE_REPLACE_SLUG_GROUP
        );
    }


    private function generate_integers_re() {
        $this->match_and_replace_to_real_regex(
            $this->url_pattern,
            self::SEARCH_ID_TAGS_INTEGERS,
            self::RE_REPLACE_INTEGER_GROUP
        );

    }

    private static function match_and_replace_to_real_regex(&$url_lazy, $search_id_tag_type, $replaceTo) {
        preg_match("/(.+:)([a-z]+)/", $search_id_tag_type,$matches);
        $search_type = $matches[2];

        $ok = preg_match_all(
            $search_id_tag_type,
            $url_lazy ,
            $matches
        );

        if ($ok) {
            foreach ($matches[1] as $key) {
                $id = $key;
                $replace = str_replace("%id%", $id, $replaceTo);
                $url_lazy = str_replace("<$id:$search_type>",$replace, $url_lazy);
            }
        }
    }


    function getParameters($url_request) {
        $params = [];
        preg_match_all(
            $this->url_pattern,
            $url_request ,
            $matches,
            PREG_PATTERN_ORDER
        );

        foreach ($matches as $key=>$value) {
            foreach ($value as $key2=>$value2) {
                $params[$key] = $value2;
            }
        }
        return $params;
    }

    function isThis($url_request) {
        return preg_match_all($this->url_pattern, $url_request, $matches);
    }
}