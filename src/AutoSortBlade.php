<?php

namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class AutoSortBlade
{

    /**
     * Create a new AutoWhereBlade instance.
     */
    public function __construct(){

    }


    public static function sort($param){
        if (count($param) === 1) {
            $param[1] = $param[0];
        }

        $sort = $sortOriginal = $param[0];
        $title = $param[1];

        $formatting_function = Config::get('autowhere.sort.formatting_function', null);

        if (!is_null($formatting_function) && function_exists($formatting_function)) {
            $title = call_user_func($formatting_function, $title);
        }

        $icon = Config::get('autowhere.sort.default_icon_set');

        foreach (Config::get('autowhere.sort.columns') as $key => $value) {
            if (in_array($sort, $value['rows'])) {
                $icon = $value['class'];
            }
        }

        if (Input::get('sort') == $sortOriginal && in_array(Input::get('order'), ['asc', 'desc'])) {
            $asc_suffix = Config::get('autowhere.sort.asc_suffix', '-asc');
            $desc_suffix = Config::get('autowhere.sort.desc_suffix', '-desc');
            $icon = $icon . (Input::get('order') === 'asc' ? $asc_suffix : $desc_suffix);
            $order = Input::get('order') === 'desc' ? 'asc' : 'desc';
        } else {
            $icon = Config::get('autowhere.sort.sortable_icon');
            $order = Config::get('autowhere.sort.default_order_unsorted', 'asc');
        }

        $param = [
            'sort' => $sortOriginal,
            'order' => $order,
        ];

        $queryString = http_build_query(array_merge(array_filter(Request::except('sort', 'order', 'page')), $param));
        $anchorClass = Config::get('autowhere.sort.anchor_class', null);
        if ($anchorClass !== null) {
            $anchorClass = 'class="' . $anchorClass . '"';
        }

        $iconAndTextSeparator = Config::get('autowhere.sort.icon_text_separator', '');

        $clickableIcon = Config::get('autowhere.sort.clickable_icon', false);
        $trailingTag = $iconAndTextSeparator . '<i class="' . $icon . '"></i>' . '</a>' ;
        if ($clickableIcon === false) {
            $trailingTag = '</a>' . $iconAndTextSeparator . '<i class="' . $icon . '"></i>';
        }

        return '<a ' . $anchorClass . ' href="'. url(Request::path() . '?' . $queryString) . '"' . '>' . htmlentities($title) . $trailingTag;
    }


}
