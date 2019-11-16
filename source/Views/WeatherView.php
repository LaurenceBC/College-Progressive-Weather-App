<?php
/**
 * View class for Weather view
 */
class WeatherView extends \SharedWeatherView {

    public function WeatherMain($Options = []) {
        ?>
        <div class="container">
        <?php $this->showWeatherSearch(); ?>
        </div><?php
        }
}
    