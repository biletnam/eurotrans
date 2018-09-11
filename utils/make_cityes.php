<?php
/**
 * Created by PhpStorm.
 * User: 45-parallel.org
 * Date: 05.09.2018
 * Time: 14:17
*/

$arTargets = array(1,2);
$arTargets[1] = 'target';
$arTargets[2] = 'target';
function prepare_headers()
{
    $headers = [];
    $headers[] = 'Content-type: application/json; charset=utf-8';
    $headers[] = 'Accept: application/json';
    return $headers;
}
foreach ($arTargets as $key =>$tem)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://erp.evrotrans.net/search_reis_v2.php?".$tem."=".$key,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER => prepare_headers(),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    $response = json_decode($response);
    if ($key == 1)
    {
        if (!(empty($response->destination)))
        {
            $arCityesFrom = array();
            foreach ($response->destination as $value)
                $arCityesFrom[] = $value;
        }
    }
    else
    {
        if (!(empty($response->destination)))
        {
            $arCityesTo = array();
            foreach ($response->destination as $value)
                $arCityesTo[] = $value;
        }
    }
}