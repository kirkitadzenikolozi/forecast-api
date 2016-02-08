<?php

require_once '../include/Retriever.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    // $headers = apache_request_headers();
    // $response = array();
    // $app = \Slim\Slim::getInstance();

    // // Verifying Authorization Header
    // if (isset($headers['Authorization'])) {
    //     $db = new DbHandler();

    //     // get the api key
    //     $api_key = $headers['Authorization'];
    //     // validating api key
    //     if (!$db->isValidApiKey($api_key)) {
    //         // api key is not present in users table
    //         $response["error"] = true;
    //         $response["message"] = "Access Denied. Invalid Api key";
    //         echoRespnse(401, $response);
    //         $app->stop();
    //     } else {
    //         global $user_id;
    //         // get user primary key id
    //         $user_id = $db->getUserId($api_key);
    //     }
    // } else {
    //     // api key is missing in header
    //     $response["error"] = true;
    //     $response["message"] = "Api key is misssing";
    //     echoRespnse(400, $response);
    //     $app->stop();
    // }
}

$app->get('/test', 'authenticate', function() {
        
            $parser = new Parser();


            $result = $parser->getLastUpdateTime();

            //var_dump($result);

            //echo $result;
        });




/**
 * Listing weather data
 * method GET
 * url /weather/currently          
 */
$app->get('/weather/currently', 'authenticate', function() {
        
            $retriever = new Retriever();
            // fetching all data
            $result = $retriever->getCurrentWeather();

            if ($result != null) {
                $response["results"] = array();
                
                for ($i=0; $i < sizeof($result); $i++) { 
                    $tmp = array();
                    $tmp['lat'] = $result[$i]['lat'];
                    $tmp['lng'] = $result[$i]['lng'];
                    $tmp['city'] = $result[$i]['city'];
                    $tmp['time'] = $result[$i]['time'];
                    $tmp['summary'] = $result[$i]['summary'];
                    $tmp['icon'] = $result[$i]['icon'];
                    $tmp['temperature'] = $result[$i]['temperature'];
                    $tmp['humidity'] = $result[$i]['humidity'];
                    $tmp['pressure'] = $result[$i]['pressure'];

                    array_push($response['results'], $tmp);
                }

            } else {
                $response["error"] = true;
                $response['message'] = "Information can't get from server";
            }
           
            echoRespnse(200, $response);
        });

/**
 * Listing weather data
 * method GET
 * url /weather/currently          
 */
$app->get('/weather/currently/:cityName', 'authenticate', function($cityName) {
        
            $retriever = new Retriever();
            // fetching all data
            $result = $retriever->getCurrentWeatherByCityName($cityName);

            if ($result != null) {
                $response["results"] = array();
                
                for ($i=0; $i < sizeof($result); $i++) { 
                    $tmp = array();
                    $tmp['lat'] = $result[$i]['lat'];
                    $tmp['lng'] = $result[$i]['lng'];
                    $tmp['time'] = $result[$i]['time'];
                    $tmp['summary'] = $result[$i]['summary'];
                    $tmp['icon'] = $result[$i]['icon'];
                    $tmp['temperature'] = $result[$i]['temperature'];
                    $tmp['humidity'] = $result[$i]['humidity'];
                    $tmp['pressure'] = $result[$i]['pressure'];

                    array_push($response['results'], $tmp);
                }

            } else {
                $response["error"] = true;
                $response['message'] = "Information can't get from server";
            }
           
            echoRespnse(200, $response);
        });


/**
 * Listing weather data
 * method GET
 * url /weather/hourly          
 */
$app->get('/weather/hourly', 'authenticate', function() {
        
            $retriever = new Retriever();
            // fetching all data
            $result = $retriever->getHourlyWeather();

            if ($result != null) {
                $response["results"] = array();
                
                for ($i=0; $i < sizeof($result); $i++) { 
                    $tmp = array();
                    $tmp['lat'] = $result[$i]['lat'];
                    $tmp['lng'] = $result[$i]['lng'];
                    $tmp['city'] = $result[$i]['city'];
                    $tmp['time'] = $result[$i]['time'];
                    $tmp['summary'] = $result[$i]['summary'];
                    $tmp['icon'] = $result[$i]['icon'];
                    $tmp['temperature'] = $result[$i]['temperature'];
                    $tmp['humidity'] = $result[$i]['humidity'];
                    $tmp['pressure'] = $result[$i]['pressure'];

                    array_push($response['results'], $tmp);
                }

            } else {
                $response["error"] = true;
                $response['message'] = "Information can't get from server";
            }
           
            //echoRespnse(200, $response);
        });



/**
 * Listing weather data
 * method GET
 * url /weather/daily          
 */
$app->get('/weather/daily/:city', 'authenticate', function($city) {

            $retriever = new Retriever();
            // fetching all data
            $result = $retriever->getDailyWeather($city);

            if ($result != null) {
                $response["results"] = array();
                
                for ($i=0; $i < sizeof($result); $i++) { 
                    $tmp = array();
                    $tmp['time'] = $result[$i]['time'];
                    $tmp['summary'] = $result[$i]['summary'];
                    $tmp['icon'] = $result[$i]['icon'];
                    $tmp['temperatureMin'] = $result[$i]['temperatureMin'];
                    $tmp['temperatureMax'] = $result[$i]['temperatureMax'];
                    //$tmp['temperature'] = $result[$i]['temperature'];
                    $tmp['humidity'] = $result[$i]['humidity'];
                    $tmp['pressure'] = $result[$i]['pressure'];

                    array_push($response['results'], $tmp);
                }

            } else {
                $response["error"] = true;
                $response['message'] = "Information can't get from server";
            }
           
            echoRespnse(200, $response);
        });



/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>