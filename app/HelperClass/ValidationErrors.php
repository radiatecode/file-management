<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 4/10/2019
 * Time: 2:44 PM
 */

namespace App\HelperClass;


class ValidationErrors
{

    public static function getErrors($validation){
        $array=[];
        //return response()->json($validation->errors(),402);
        foreach($validation->messages()->getMessages() as $field_name => $messages) {
            // Go through each message for this field.
            foreach($messages AS $message) {
                $array[$field_name]=$message;
                //$array[$field_name."_code"]=422;
            }
        }
        return $array;
    }

}