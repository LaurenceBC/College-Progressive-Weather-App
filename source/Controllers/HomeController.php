<?php
/**
 * Home Controller class.
 */
class HomeController {

    /**
     *ControllerView var
     */
    var $ControllerView;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->ControllerView = new \HomeView();
    }

    /**
     * Default action if no method.
     * 
     * @return type
     */
    public function defaultAction() {
        $this->Home();
        return;
    }

    /**
     * Creates the home page.
     * 
     * @return type
     */
    public function Home() {
        $DemoWeather = new \WeatherController();
        $this->ControllerView->Home();
        $DemoWeather->Name('London');
        return;
    }

    public function About() {
        
    }

}
