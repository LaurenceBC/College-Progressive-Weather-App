<?php

//Use Database abstraction libary.
use TinyDatabaseAccessLayer\DatabaseRetrieve as DBRETRIEVE;
//Use Weather data API abstraction libary.
use ProgressiveWeatherApp\WeatherAPIAccess;
/**
 * Weather Controller class.
 */
class WeatherController {

    /**
    *ControllerView var
    */
    var $ControllerView;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->ControllerView = new \WeatherView();
    }

    /**
     * Default action if no method.
     * 
     * @return type
     */
    public function defaultAction() {
        $this->WeatherMain();
    }

    /**
     * 
     * @return type
     */
    public function WeatherMain() {
        $this->ControllerView->WeatherMain();
        return;
    }

    /**
     * Looks up weather via the weather API using a location name.
     * 
     * @param type $Name
     * @return type
     */
    public function Name($Name = null) {

        try {
            $WeatherData = WeatherAPIAccess::getCurrentByCity($Name);
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            $this->ControllerView
                    ->PromptWindow('Opps', 'Looks like ' . $Name . ' isnt a real place...', ['OK' => '/Weather']);
            return;
        }
        $Forecast = WeatherAPIAccess::getFiveDayForecastByCity($Name);

        $this->showWeather($WeatherData, $Forecast, null);
        return;
    }
    
    /**
     * Looks up weather via the weather API using a postcode.
     * 
     * @param type $PostCode
     * @return type
     */
    public function PostCode($PostCode) {
        try {
            $WeatherData = WeatherAPIAccess::getCurrentByPostCode($PostCode);
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            $this->ControllerView->PromptWindow('Opps', 'Looks like ' . $PostCode . ' isnt a real place...', ['OK' => '/Weather']);
        return;
            
        }

        $Forecast = WeatherAPIAccess::getFiveDayForecastByPostCode($PostCode);
        $this->showWeather($WeatherData, $Forecast);
        return;
    }

    /**
     * Looks up weather via the weather API using Geo coordinates.
     * 
     * @param type $Lat
     * @param type $Lon
     * @return type
     */
    public function GeoCoords($Lat, $Lon) {
        try {
            $WeatherData = WeatherAPIAccess::getCurrentByGeoCords($Lat, $Lon);
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            $this->ControllerView->PromptWindow('Opps', 'Looks like ' . $PostCode . ' isnt a real place...', array('OK' => '/Weather'));
            return;
        }

        $Forecast = WeatherAPIAccess::getFiveDayForecastByCords($Lat, $Lon);
        $this->showWeather($WeatherData, $Forecast);

        return;
    }

    /**
     * Checks if the weather for a location (via GPS) is
     * the users favourite weather.
     * 
     * Returns a record or null.
     * 
     * @param type $Lat
     * @param type $Lon
     * @return type
     */
    protected function checkIsUsersFavouriteWeather($Lat, $Lon) {

        $UUID = null;

        if (isset($_SESSION['Login']['isLOGGEDIN'])) {
            $UUID = $_SESSION['Login']['UUID'];

            $UserFavouriteWeather = new DBRETRIEVE();
            $UserFavouriteWeather->query('SELECT HomePage FROM UsersFavoriteWeather WHERE UUID = :UUID AND Lon = :Lon AND Lat = :Lat');
            $UserFavouriteWeather->bind(':UUID', $UUID);
            $UserFavouriteWeather->bind(':Lon', $Lon);
            $UserFavouriteWeather->bind(':Lat', $Lat);

            $UserFavouriteWeather->execute();

            $Record = $UserFavouriteWeather->single();

            if (!empty($Record)) {
                return $Record;
            }
        }
        return null;
    }

    /**
     * Builds the weather view using passed weather data
     * and forecast.     * 
     * 
     * @param type $WeatherData
     * @param type $Forecast
     * @return type
     */
    protected function showWeather($WeatherData, $Forecast) {

        //Convert forecast next 24hours to 2d array [time
        $HourlyTemp = array();
        $UserLoggedin = isset($_SESSION['Login']['isLOGGEDIN']);

        //Find out if this is users weather
        $UsersFav = $this->checkIsUsersFavouriteWeather($WeatherData->coord->lat, $WeatherData->coord->lon);
        $OnUsersHomePage = false;
        if (!empty($UsersFav)) {
            $OnUsersHomePage = $UsersFav['HomePage'];
            $UsersFav = true;
        }
        
        $ViewOptions = array('ISLOGGEDIN' => $UserLoggedin, 'isFavorite' => $UsersFav, 'onHome' => $OnUsersHomePage);

        $this->ControllerView->showWeatherContainer($WeatherData, $Forecast, $HourlyTemp, $ViewOptions);
        return;
    }

}
