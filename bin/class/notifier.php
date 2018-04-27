<?php
/**
 * Created by IntelliJ IDEA.
 * User: hoang
 * Date: 03/10/2017
 * Time: 19:33
 */

class notifier
{
    public static function sendMessage($msg)
    {
        $content = array(
            "en" => $msg
        );

        $fields = array(
            'app_id' => "d756e9e2-2987-4b9b-870c-8a36afe3868d",
            'included_segments' => array('All'),
            // 'data' => array("foo" => "bar"),
            'contents' => $content
        );

        $fields = json_encode($fields);
        /*print("\nJSON sent:\n");
        print($fields);*/

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic MTg1MzUzZGMtMTgwYy00NGFiLWE4MTYtZDBlNzIzMTFkODdh'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public static function send($msg)
    {

        $message = ucfirst(strtolower($msg));
        $response = self::sendMessage($message);
        $return["allresponses"] = $response;
        $return = json_encode($return);

        /*print("\n\nJSON received:\n");
        print($return);
        print("\n");*/

    }
}