<?php

if(!function_exists('validateData')){
    function validateData($data,$rules){
        $validator = Illuminate\Support\Facades\Validator::make($data,$rules);
        if($validator->fails()){
            return response(['errors'=>$validator->errors()],400);
        }
    }
}
