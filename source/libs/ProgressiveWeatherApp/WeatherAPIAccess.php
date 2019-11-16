<?php

namespace ProgressiveWeatherApp;

require_once $_SERVER["DOCUMENT_ROOT"] . '//..//libs/GuzzleHttp/Psr7/functions.php';

//This is needed because something is broke :/

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class is responible for consuming openweather api.
 */
class WeatherAPIAccess {

    //also called appkey
    private static $apikey = '14b07ab4a980f1c5f484fb7bdbd25257';

    private static function getWeatherJSON($Type, $Queries = array()) {
        //Todo refactor into try
        
        $APIQuery['query'] = $Queries;
        $client = new GuzzleClient();
        $res = $client->request('GET', 'http://api.openweathermap.org/data/2.5/'. $Type .'?', $APIQuery);
        
        $APIResponse = \GuzzleHttp\json_decode($res->getBody()->getContents());
        
        //Retruns a JSON response
        return $APIResponse;
    }
    
    
    public static function getCurrentByCity($City)
    {
        if (strpos($City, ',') === false) {
            $City = $City.',GB';
        }
        $Response = self::getWeatherJSON('weather', array('appid' => self::$apikey, 'q' => $City, 'units' => 'metric'));   
       return $Response;               
    }
    
    
    
    public static function getCurrentByGeoCords($Lat, $Lon)
    {
        
       $Response = self::getWeatherJSON('weather', array('appid' => self::$apikey, 'lat' => $Lat, 'lon' => $Lon, 'units' => 'metric'));
       return $Response;
    }
    
    public static function getCurrentByPostCode($PostCode)
    {
       $Response = self::getWeatherJSON('weather', array('appid' => self::$apikey, 'zip' => $PostCode . ',UK', 'units' => 'metric'));   
       return $Response;   
    }
    

    
    
    //5Day ForeCase
    
    public static function getFiveDayForecastByCity($City)
    {
           $Response = self::getWeatherJSON('forecast', 
                   array('appid' => self::$apikey, 'q' => $City));   
       return $Response;
    }
    
    public static function getFiveDayForecastByCords($Lat, $Lon)
    {
          $Response = self::getWeatherJSON('forecast', 
                  array('appid' => self::$apikey, 'lat' => $Lat, 'lon' => $Lon));
       return $Response;
    }
    
    public static function getFiveDayForecastByPostCode($PostCode)
    {
        
    }
    
    
            
    

}
