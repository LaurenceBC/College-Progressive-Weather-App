<?php


//Use Database abstraction libary.
use TinyDatabaseAccessLayer\DatabaseInsert as DBINSERT;
use TinyDatabaseAccessLayer\DatabaseRetrieve as DBRETRIEVE;
//Use Weather data API abstraction libary.
use ProgressiveWeatherApp\WeatherAPIAccess;

/**
 * Weather Controller class.
 */
class myWeatherController extends \BaseController {

    /**
    *ControllerView var
    */
    var $ControllerView;

    /**
    * Class constructor.
    */
    public function __construct() {
        $this->ControllerView = new \myWeatherView();
    }

        /**
      * Default action if no method.
      * 
      * @return type
      */
    public function defaultAction() {
        $this->checkLoggedIn();
        $this->myWeather();
    }

    
    public function Favorites($Action = null) {
        $this->checkLoggedIn();

        //Get the users ID        
        $UUID = $_SESSION['Login']['UUID'];

        //Check if this is a post request with data        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

            $PostData = filter_input(INPUT_POST, 'FavoriteData', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

            switch ($Action) {
                case 'Add' :
                    //Add favorite.
                    if ($this->addFavorite($UUID, $PostData['Lon'], $PostData['Lat'])) {
                        $this->ControllerView->
                                PromptWindow('Favorite weather added', 'Redirecting to your weather page', array('AUTOREDIRECT' => '/myWeather'));
                    } else {
                        echo 'error';
                    }
                    return;
                case 'Remove' :
                    if ($this->removeFavorite($UUID, $PostData['Lon'], $PostData['Lat'])) {
                        $this->ControllerView->
                                PromptWindow('Removed', 'Removed', array('AUTOREDIRECT' => '/myWeather'));
                    } else {
                        echo 'error';
                    }
                    return;
                default :   
                //Error
            }
        } else {              
                $this->myWeather();
        }
        return;
    }

    public function HomePage() {
        $this->checkLoggedIn();


        //Check if this is a post request with data        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

            $UUID = $_SESSION['Login']['UUID'];
            $PostData = filter_input(INPUT_POST, 'HomePageData', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

            if ($this->setFavoriteOnHome($UUID, $PostData['Lon'], $PostData['Lat'], $PostData['onHome'])) {
                $this->ControllerView->
                        PromptWindow('Favorite weather added', 
                                     'Redirecting to your weather page', 
                                      array('AUTOREDIRECT' => '/myWeather'));
            } else {
                echo 'error';
            }
        } else {
            echo 'no post';
        }
        return;
    }

    /** 
     * 
     * 
     * 
     * 
     * @return type
     */
    public function myWeather($onHomeOnly = false) {
        $this->checkLoggedIn();
        //Show all users weather
        $UUID = $_SESSION['Login']['UUID'];
        $UsersFavoriteWeather = array();

        $getUserFavorites = $this->getAllUsersFavoriteWeather($UUID) ?? null;

     

        if ($getUserFavorites !== null) {
            $tmparrayweather = array();
            
            foreach ($getUserFavorites as $UserFavorite) {

                //Get users fav lat and lon for weather data lookup.
                $Lat = $UserFavorite['Lat'];
                $Lon = $UserFavorite['Lon'];
                //Get options for this favorite (ie isfav,is home)
                $UserWeatherOptions = array('ISLOGGEDIN' => true, 'isFavorite' => true, 'onHome' => $UserFavorite['HomePage']);
                $tmparrayweather['Current'] = WeatherAPIAccess::getCurrentByGeoCords($Lat, $Lon);
                $tmparrayweather['Options'] = $UserWeatherOptions;
                
                array_push($UsersFavoriteWeather, $tmparrayweather);

            }
          
          
        }
  
        $this->ControllerView->myWeather($UsersFavoriteWeather);
        return;
    }

    //Internal functions.


    /**
     * Adds weather.
     * 
     * @param type $UserID
     * @param type $Lon
     * @param type $Lat
     * @return type
     */
    private function addFavorite($UserID, $Lon, $Lat) {

        $Database = new DBINSERT();
        $Database->query('INSERT INTO UsersFavoriteWeather (UUID, Lon, Lat) VALUES (:UUID,:Lon,:Lat)');
        $Database->bind(':UUID', $UserID);
        $Database->bind(':Lon', $Lon);
        $Database->bind(':Lat', $Lat);

        return $Database->execute();
    }

    /**
     * Removes weather 
     * 
     * @param type $UserID
     * @param type $Lon
     * @param type $Lat
     * @return type
     */
    private function removeFavorite($UserID, $Lon, $Lat) {

        $Database = new DBRETRIEVE();
        $Database->query('DELETE FROM UsersFavoriteWeather WHERE UUID = :UUID AND Lat = :Lat AND Lon = :Lon');
        $Database->bind(':UUID', $UserID);
        $Database->bind(':Lon', $Lon);
        $Database->bind(':Lat', $Lat);

        return $Database->execute();
    }

    /**
     * Changes a users weather to be shown on homepage.
     * 
     * 
     * @param type $UserID
     * @param type $Lon
     * @param type $Lat
     * @param type $onHome
     * @return type
     */
    private function setFavoriteOnHome($UserID, $Lon, $Lat, $onHome) {
        $Database = new DBRETRIEVE();
        $Database->query('UPDATE UsersFavoriteWeather SET HomePage = :OnHome WHERE UUID = :UUID AND Lat = :Lat AND Lon = :Lon');
        $Database->bind(':UUID', $UserID);
        $Database->bind(':Lon', $Lon);
        $Database->bind(':Lat', $Lat);
        $Database->bind(':OnHome', $onHome);


        return $Database->execute();
    }

    
    /**
     * Get all users weather with their UUID.
     * 
     * @param type $UUID
     * @return type
     */
    private function getAllUsersFavoriteWeather($UUID) {
        $Database = new DBRETRIEVE();
        $Database->query('SELECT * FROM UsersFavoriteWeather WHERE UUID = :UUID ORDER BY DateAdded DESC');
        $Database->bind(':UUID', $UUID);

        return $Database->resultSet();
    }

}
