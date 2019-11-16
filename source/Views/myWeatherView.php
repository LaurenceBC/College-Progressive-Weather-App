<?php
/**
 * View class for myWeather view
 */
class myWeatherView extends \SharedWeatherView {

    /**
     * Displays all users weather passed to view.
     * 
     * @param type $UsersFavoriteWeather
     * @param type $Options
     */
    public function myWeather($UsersFavoriteWeather, $Options = array()) {


        if ($UsersFavoriteWeather === null) {
            echo 'You dont have any favorite weather';
        } else {
            ?><div class="row"><?php
            foreach ($UsersFavoriteWeather as $Weather) {
                echo '<div class="col p-3">' .
                $this->showWeatherContainer($Weather['Current'], null, null, $Weather['Options']) .
                '</div>';
            }
            ?></div><?php
            }
        }

    }
    