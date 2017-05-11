<?php

namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class AutoPageBlade
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
        $selector = isset($param[0]) ? $param[0] : ".panel-table";
        $r = '<script>
        $(document).ready(function(){
            $(".pagination a").click(function(e){
                e.preventDefault();
                var url = $(this).attr("href");
                $.get(url,{},function(data){
                    $("body").find("'.$selector.'").replaceWith($(data).find("'.$selector.'"));
                    var pagetitle = $(data).find("title").text() || $("title").text();
                    window.history.pushState({"html":data,"pageTitle":pagetitle}, pagetitle, url);
                });
            });
        });
        </script>';
        return $r;
    }


    public static function pages($param){
        if(isset( $param[0] ))
            return $param[0]->appends(Request::except('page'))->render();
    }


}
