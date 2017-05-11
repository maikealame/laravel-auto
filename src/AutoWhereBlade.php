<?php

namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class AutoWhereBlade
{

    /**
     * Create a new AutoWhereBlade instance.
     */
    public function __construct(){

    }


    // @autowherefilter("id")
    // @autowherefilter("id", 5)
    // @autowherefilter("id", 5, "checked")
    /*
     * Call blade function to get filter HTTP params in view
     */
    public static function filter($param){
        $field = isset($param[0]) ? $param[0] : null;
        $value = isset($param[1]) ? $param[1] : null;
        $option = isset($param[2]) ? $param[2] : null;
        $getvalue = isset(Request::get('filter')[$field]) ? Request::get('filter')[$field] : "";
        if($value == null) {
            // 1
            return $getvalue;
        }else{
            if($option == null){
                // 2
                if($getvalue == $value) return "selected";
            }else{
                // 3
                if($getvalue == $value) return $option;
            }
        }
        return "";
    }


    /*
     * Create <script> js for use auto filter get HTTP params
     */
    public static function script($param){
        $btn = isset($param[0]) ? $param[0] : ".table_filter";
        $script = '<script>
        function getUrlParameters() {
            var re = /([^&=]+)=?([^&]*)/g;
            var decode = function (str) {
                        return decodeURIComponent(str.replace(/\+/g, " "));
            };
            function createElement(params, key, value) {
                key = key + "";
        
                if (key.indexOf("[") !== -1) {
                    var list = key.split("[");
                    key = list[0];
                    var list = list[1].split("]");
                    var index = list[0]
                    if (index == "") { // key[]
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
                    if (key.indexOf(".") !== -1) {
                        var list = key.split(".");
                        var new_key = key.split(/\.(.+)?/)[1];
                    if (!params[list[0]]) params[list[0]] = {};
                    if (new_key !== "") {
                        createElement(params[list[0]], new_key, value);
                    } else console.warn("parseParams :: empty property in key \'" + key + "\'");
                } else
                    {
                        if (!params) params = {};
                    params[key] = value;
                }
            }
            query = window.location + "";
            var params = {}, e;
            if (query) {
                if (query.indexOf("#") !== -1) {
                    query = query.substr(0, query.indexOf("#"));
                }
                if (query.indexOf("?") !== -1) {
                    query = query.substr(query.indexOf("?") + 1, query.length);
                } else return {};
        
                if (query == "") return {};
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



        // Filter for tables

        function table_filter(btn){
            var tr = btn.closest("tr");
            var data = {};
            $(tr).find("th,td").each(function(i,e){
                var t = $(e).find(" input, select, > textarea, .live-search").not("[type=hidden]").not("'.$btn.'");
                if(t.length)
                    if(t.val() != ""){
                        var name = t.attr("data-name") ? t.attr("data-name") : t.attr("name");
                        if(t.attr("data-type")) data[name] = {"type" :  t.attr("data-type"), "value" : t.val()};
                        else data[name] = t.val();
                    }
            });
            var p = getUrlParameters();
            delete p["page"];
            var final = "";
            if($.map(data, function(n, i) { return i; }).length > 0) {
                p["filter"] = data;
            }else{
                delete p["filter"];
            }
        
            if($.map(p, function(n, i) { return i; }).length > 0) {
                final = "?" + $.param(p);
            }
            window.location.href = window.location.href.split("?")[0] + final;
        }
        
        function addUrlParameter(key,value){
            _url = location.href;

            if( _url.indexOf("?") != -1 ){
                if(_url.split("?")[1] == ""){
                    _url = _url.replace("?","");
                    _url += "?";
                }else {
                    _url += "&";
                }
            }else{
                _url += "?";
            }

            _url += key;
            if( typeof value != "undefined" ) _url += "="+value;
            window.location.href = _url;
        }
        function removeUrlParameter(key){
                var sourceURL = location.href;
                var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
            if (queryString !== "") {
                params_arr = queryString.split("&");
                for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                    param = params_arr[i].split("=")[0];
                    if (param === key) {
                        params_arr.splice(i, 1);
                    }
                }
                rtn = rtn + "?" + params_arr.join("&");
            }
            window.location.href = rtn;
        }
        
        var printArray = function(o){
            var str="";

            for(var p in o){
                if(typeof o[p] == "string"){
                    str+= p + ": " + o[p]+"; </br>";
                }else{
                    str+= p + ": { </br>" + print(o[p]) + "}";
                }
            }
        
            return str;
        };
        $(document).ready(function(){
            $("body").on("click","'.$btn.'",function(){table_filter($(this));});
        });
        
        </script>';
        return $script;
    }


}
