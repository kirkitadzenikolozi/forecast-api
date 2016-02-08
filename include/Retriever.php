<?php 

class Retriever {


    public function getCurrentWeather() {

    	// cities array 
    	$cities = array('Tbilisi', 'Batumi', 'Kobuleti', 'Kutaisi', 'Zugdidi', 'Kazbegi', 'Telavi');
    	//$cities = array('Tbilisi');
    	$response = array();

    	for ($i=0; $i < sizeof($cities); $i++) { 
    		$locationUrl = 'http://maps.google.com/maps/api/geocode/json?address=' . $cities[$i];

    		$obj = json_decode(file_get_contents($locationUrl), true);

    		$locationObject = $obj['results'][0]['geometry']['location'];

    		$lat = $locationObject['lat'];
    		$lng = $locationObject['lng'];

    		$location = $lat . ',' . $lng;


    		// SSL
			$arrContextOptions=array(
			    "ssl"=>array(
			        "verify_peer"=>false,
			        "verify_peer_name"=>false,
			    ),
			);  

		
    		$forecastUrl = 'https://api.forecast.io/forecast/a3f325a6ff94325316cc457d8981996d/' . $location;
    		$forecastResponse = json_decode(file_get_contents($forecastUrl, false, stream_context_create($arrContextOptions)), true);
    		$forecastCurrently = $forecastResponse['currently'];

    		$tmp = array();
    		$tmp['lat'] = $lat;
    		$tmp['lng'] = $lng;
    		$tmp['city'] = $cities[$i];
    		$tmp['time'] = $forecastCurrently['time'];
    		$tmp['summary'] = $forecastCurrently['summary'];
    		$tmp['icon'] = $forecastCurrently['icon'];
    		$tmp['temperature'] = $this->convertToCelsius($forecastCurrently['temperature']);
    		$tmp['humidity'] = $forecastCurrently['humidity'];
    		$tmp['pressure'] = $forecastCurrently['pressure'];

    		array_push($response, $tmp);
    	}

    	return $response;
		
    }

    public function getCurrentWeatherByCityName($cityName) {

        $response = array();

        $locationUrl = 'http://maps.google.com/maps/api/geocode/json?address=' . $cityName;

        $obj = json_decode(file_get_contents($locationUrl), true);

        $locationObject = $obj['results'][0]['geometry']['location'];

        $lat = $locationObject['lat'];
        $lng = $locationObject['lng'];

        $location = $lat . ',' . $lng;


        // SSL
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  

    
        $forecastUrl = 'https://api.forecast.io/forecast/a3f325a6ff94325316cc457d8981996d/' . $location;
        $forecastResponse = json_decode(file_get_contents($forecastUrl, false, stream_context_create($arrContextOptions)), true);
        $forecastCurrently = $forecastResponse['currently'];

        $tmp = array();
        $tmp['lat'] = $lat;
        $tmp['lng'] = $lng;
        $tmp['time'] = $forecastCurrently['time'];
        $tmp['summary'] = $forecastCurrently['summary'];
        $tmp['icon'] = $forecastCurrently['icon'];
        $tmp['temperature'] = $this->convertToCelsius($forecastCurrently['temperature']);
        $tmp['humidity'] = $forecastCurrently['humidity'];
        $tmp['pressure'] = $forecastCurrently['pressure'];

        array_push($response, $tmp);

        return $response;
        
    }

    public function getHourlyWeather() {
    	// cities array 
        $cities = array('Tbilisi', 'Batumi', 'Kobuleti', 'Kutaisi', 'Zugdidi', 'Kazbegi', 'Telavi');
        //$cities = array('Tbilisi');
        $response = array();

        for ($i = 0; $i < sizeof($cities); $i++) {

            $locationUrl = 'http://maps.google.com/maps/api/geocode/json?address=' . $cities[$i];

            $obj = json_decode(file_get_contents($locationUrl), true);

            $locationObject = $obj['results'][0]['geometry']['location'];

            $lat = $locationObject['lat'];
            $lng = $locationObject['lng'];

            $location = $lat . ',' . $lng;


            // SSL
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );  


            $forecastUrl = 'https://api.forecast.io/forecast/a3f325a6ff94325316cc457d8981996d/' . $location;
            $forecastResponse = json_decode(file_get_contents($forecastUrl, false, stream_context_create($arrContextOptions)), true);
            $forecastHourly = $forecastResponse['hourly']['data'];

            for ($j=0; $j < sizeof($forecastHourly); $j++) { 
                echo $forecastHourly[$j]['time'] . '</br>';
                echo $forecastHourly[$j]['summary'] . '</br >';
            }

            

            //var_dump($forecastHourly);

            // $tmp = array();
            // $tmp['lat'] = $lat;
            // $tmp['lng'] = $lng;
            // $tmp['city'] = $cities[$i];
            // $tmp['time'] = $forecastHourly['time'];
            // $tmp['summary'] = $forecastHourly['summary'];
            // $tmp['icon'] = $forecastHourly['icon'];
            // $tmp['temperature'] = $this->convertToCelsius($forecastHourly['temperature']);
            // $tmp['humidity'] = $forecastHourly['humidity'];
            // $tmp['pressure'] = $forecastHourly['pressure'];
            // $tmp['ozone'] = $forecastHourly['ozone'];

            // array_push($response, $tmp);
        }

        return $response;
    }

     public function getDailyWeather($city) {

        $response = array();

        $locationUrl = 'http://maps.google.com/maps/api/geocode/json?address=' . $city;

        $obj = json_decode(file_get_contents($locationUrl), true);

        $locationObject = $obj['results'][0]['geometry']['location'];

        $lat = $locationObject['lat'];
        $lng = $locationObject['lng'];

        $location = $lat . ',' . $lng;


        // SSL
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  


        $forecastUrl = 'https://api.forecast.io/forecast/a3f325a6ff94325316cc457d8981996d/' . $location;
        $forecastResponse = json_decode(file_get_contents($forecastUrl, false, stream_context_create($arrContextOptions)), true);
        $forecastDaily = $forecastResponse['daily']['data'];

        for ($j=0; $j < sizeof($forecastDaily); $j++) { 
            //echo $forecastDaily[$j]['time'] . '</br>';

            $tmp = array();
            $tmp['time'] = $forecastDaily[$j]['time'];
            $tmp['summary'] = $forecastDaily[$j]['summary'];
            $tmp['icon'] = $forecastDaily[$j]['icon'];
            $tmp['temperatureMin'] = $this->convertToCelsius($forecastDaily[$j]['temperatureMin']);
            $tmp['temperatureMax'] = $this->convertToCelsius($forecastDaily[$j]['temperatureMax']);
            //$tmp['temperature'] = $this->convertToCelsius($forecastDaily[$j]['temperature']);
            $tmp['humidity'] = $forecastDaily[$j]['humidity'];
            $tmp['pressure'] = $forecastDaily[$j]['pressure'];
            $tmp['ozone'] = $forecastDaily[$j]['ozone'];


            array_push($response, $tmp);
        }

        //echo sizeof($forecastDaily);

        // echo $forecastResponse['time'] . '</br>';
        // echo $forecastResponse['summary'] . '</br >';

        //var_dump($forecastDaily);

        return $response;
    }

    private function convertToCelsius($fahrenheit) {
    	return round(($fahrenheit - 32) / 1.8);
    }

  
}

?>