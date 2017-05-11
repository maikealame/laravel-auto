<?php

namespace Auto;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class AutoPageBlade
{

    /**
     * Create a new AutoWhereBlade instance.
     */
    public function __construct(){

    }



    /*
     *
     */
    public static function async($param){
        $r = '<script>
        $(document).ready(function(){
            $(".pagination a").click(function(e){
                e.preventDefault();
                $.get($(this).attr("href"),{},function(data){
                    $("html").replaceWith(data);
                });
            });
        });
        </script>';
    }


    public static function pages($param){
        if(isset( $param[0] ))
            return $param[0]->appends(Request::except('page'))->render();
    }


}
