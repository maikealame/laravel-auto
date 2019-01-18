<?php

namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class AutoPaginateBlade
{

    /**
     * Create a new AutoPageBlade instance.
     */
    public function __construct(){

    }



    /*
     *
     */
    public static function async($param){
        $selector = isset($param[0]) ? $param[0] : [".panel-table"];
        if(!is_array($selector)) $selector = [$selector];
        $replace = "";
        foreach($selector as $s) {
            $replace .= '$(document).find("'.$s.'").each( function(k,el){ 
                $(el).replaceWith($(data).find("'.$s.'").get(k));
            });';
        }
        $changeUrl = isset($param[1]) ? $param[1] : true;
        $r = '<script>
        $(document).ready(function(){
            $(document).on("click",".pagination a",function(e){
                e.preventDefault();
                var url = $(this).attr("href");
                __autoLoadAsync(url);
            });
            
            function __autoLoadAsync(url){
                $.get(url,{},function(data){
                    '. $replace .'
                    $(window).resize();
                    $(document).trigger("autopageasync");
                    var pagetitle = $(data).find("title").text() || $("title").text();';
        if($changeUrl) $r .= 'window.history.pushState({"html":data,"pageTitle":pagetitle}, pagetitle, url);';
        $r .=   '});
            }
        });
        </script>';
        return $r;
    }


    public static function pages($param){
        if(isset( $param[0] ))
            if($param[0]) {
                if(isset($param[1])) return $param[0]->appends(Request::except('page'))->render($param[1]);
                return $param[0]->appends(Request::except('page'))->render();
            }
        return null;
    }

    public static function length($param){
        $lengths = Config::get("laravelauto.pages.length");
        $default = Config::get("laravelauto.pages.default_length");
        $paginateObject = isset( $param[0] ) ? $param[0] : null;
        $length = Request::has("length") ? Request::get("length") : ($paginateObject ? $paginateObject->perPage() : $default);
        $overwriteLength = Request::has("length") ? true : false;
        $total = $paginateObject ? $paginateObject->total() : $lengths[count($lengths)];
        $r = '
        <select class="pagination-length">';
        if( !in_array($length,$lengths)) {
            array_push($lengths,$length);
        }
        if( !in_array($default,$lengths)) {
            array_push($lengths,$default);
        }
        asort($lengths);

        $last = false;
        foreach($lengths as $l) {
            $checked = ($length == $l) ? "selected" : null;
            if ($total > $l && !$last) {
                $last = false;
                $r .= '<option value = "' . $l . '" '.$checked.'>
                ' . $l . '
                </option >';
            }else{
                if($last) continue;
                else{
                    $r .= '<option value = "' . $total . '" '.$checked.'>
                    ' . $total . '
                    </option >';
                    $last = true;
                }
            }
        }

        $r .= '</select>
        <script>
            function lengthGetUrlParameters() {
                var re = /([^&=]+)=?([^&]*)/g;
                var decode = function (str) {
                    return decodeURIComponent(str.replace(/\+/g, \' \'));
                };
                function createElement(params, key, value) {
                    key = key + \'\';
            
                    if (key.indexOf(\'[\') !== -1) {
                        var list = key.split(\'[\');
                        key = list[0];
                        var list = list[1].split(\']\');
                        var index = list[0]
                        if (index == \'\') { // key[]
                            if (!params) params = {};
                            if (!params[key] || !$.isArray(params[key])) params[key] = new Array();
                            params[key].push(value);
                        } else // key[value]
                        {
                            if (!params) params = {};
                            if (!params[key] || !$.isPlainObject(params[key])) params[key] = {};
                            params[key][index] = value;
                        }
                    } else
                    if (key.indexOf(\'.\') !== -1) {
                        var list = key.split(\'.\');
                        var new_key = key.split(/\.(.+)?/)[1];
                        if (!params[list[0]]) params[list[0]] = {};
                        if (new_key !== \'\') {
                            createElement(params[list[0]], new_key, value);
                        } else console.warn(\'parseParams :: empty property in key "\' + key + \'"\');
                    } else
                    {
                        if (!params) params = {};
                        params[key] = value;
                    }
                }
                query = window.location + \'\';
                var params = {}, e;
                if (query) {
                    if (query.indexOf(\'#\') !== -1) {
                        query = query.substr(0, query.indexOf(\'#\'));
                    }
                    if (query.indexOf(\'?\') !== -1) {
                        query = query.substr(query.indexOf(\'?\') + 1, query.length);
                    } else return {};
            
                    if (query == \'\') return {};
                    while (e = re.exec(query)) {
                        var key = decode(e[1]);
                        var value = decode(e[2]);
                        console.log(key);
                        console.log(value);
                        createElement(params, key, value);
                    }
                }
                return params;
            }
            $("body").on("change",".pagination-length",function(){
                if(!$(this).val()) return;
                var p = lengthGetUrlParameters();
                delete p["page"];
                p.length = $(this).val();
                if(typeof __autoLoadAsync == "function"){
                    __autoLoadAsync(window.location.href.split("?")[0] + "?" + $.param( p ));
                }else
                    window.location.href = window.location.href.split("?")[0] + "?" + $.param( p );
            });
        </script>';
        return $r;
    }


}
