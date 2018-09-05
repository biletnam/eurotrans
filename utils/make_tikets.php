<?php
/**
 * Created by PhpStorm.
 * User: 45-parallel.org
 * Date: 05.09.2018
 * Time: 15:34
 */
if (isset($_GET))
{
    $stripGet = array_map('strip_tags', $_GET);
}

$fromCity = isset($stripGet['from']) ? $stripGet['from'] : '';
$toCity = isset($stripGet['to']) ? $stripGet['to'] : '';
$dateRoutes = isset($stripGet['date']) ? $stripGet['date'] : '';
$adult = isset($stripGet['adult']) ? intval($stripGet['adult']) : 0;
$children = isset($stripGet['children']) ? intval($stripGet['children']) : 0;

$dateSearch = "date_search=$dateRoutes";
$placeStart = "place_start=$fromCity";
$placeEnd = "place_end=$toCity";

if (!(function_exists(prepare_headers)))
{
    function prepare_headers()
    {
        $headers = [];
        $headers[] = 'Content-type: application/json; charset=utf-8';
        $headers[] = 'Accept: application/json';
        return $headers;
    }
}

$passenger = $adult + $children;

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://erp.evrotrans.net/search_reis.php?". $dateSearch ."&". $placeStart ."&". $placeEnd,
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
$arTikets = $response;


