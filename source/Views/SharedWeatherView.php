<?php
/**
 * View class : Used to output shared weather related views with the
 * Weather and myWeather controller.
 */
class SharedWeatherView extends \SharedView {

    /**
     * Outputs the weather data.
     * 
     * @param array $WeatherData
     * @param array $ForecastData
     * @param array $HourlyTempData
     * @param array $Options
     */
    public function showWeatherContainer($WeatherData, $ForecastData, $HourlyTempData, $Options = []) {
        //Options
        //Options['UserLoggedIn'] = Shows user cotrols
        //['isFav'] = Is one of the users fav show remove or show add if not.
        //['onHome'] = On the home page show remove
        ?>
        <div class='container'>
            <div class="card border-info shadow">
                <div class="card-header">
                    <img src="<?php echo 'https://progressiveweatherapp.com/images/weathericons/' . $WeatherData->weather[0]->icon . '.png'; ?>" class="float-left">
                    <h6> Weather in <?php echo $WeatherData->name; ?></h6>
                </div>
                <div class="card-body">
                    <div class="row"> 
                        <div class="col-sm">
                            <!--Weather description-->
                            <div class="row">
                                The current weather today in <?php echo $WeatherData->name . ',' . $WeatherData->sys->country; ?> on 
                                <?php echo date("Y/m/d") ?? null ?> as of <?php echo date("H:i") ?? null; ?> is,

                                <?php echo $WeatherData->weather[0]->description; ?> with a maximum temperature of 
                                <?php echo $WeatherData->main->temp_max; ?>℃ and a low of  <?php echo $WeatherData->main->temp_min; ?>℃
                            </div>
                            <!--Weather data-->
                            <div class="row border">
                                <div class="container">
                                    <h6>Weather data</h6>
                                </div>
                                <div class="row p-1">
                                    <div class="col">
                                        <h6>Temperature: <span class="label label-default"><?php echo $WeatherData->main->temp; ?>℃</span></h6>
                                        <h6>Wind speed: <span class="label label-default"><?php echo $WeatherData->wind->speed; ?>mps</span></h6>
                                        <h6>Humidity:  <span class="label label-default"><?php echo $WeatherData->main->humidity; ?>%</span></h6>
                                    </div>
                                    <div class="col">
                                        <h6>Wind direction: <span class="label label-default"><?php echo $WeatherData->wind->deg ?? null; ?></span></h6>
                                        <h6>Pressure: <span class="label label-default"><?php echo $WeatherData->main->pressure; ?></span></h6>
                                        <h6>GPS Coords: <span class="label label-default"><?php echo $WeatherData->coord->lat . ',' . $WeatherData->coord->lon; ?></span></h6>
                                    </div>

                                </div>
                            </div>
                            <!--Weather data-->
                            <!--User controls-->
                            <div class="row text-center p-1">
                                <?php
                                if ($Options['ISLOGGEDIN']) {

                                    if (array_key_exists('isFavorite', $Options) && $Options['isFavorite'] === true) {

                                        echo '<div class="container"><form method="POST" action="/myWeather/Favorites/Remove">'
                                        . '<input type="hidden" id="lat" name="FavoriteData[Lat]" value="' . $WeatherData->coord->lat . '"/>'
                                        . '<input type="hidden" id="lat" name="FavoriteData[Lon]" value="' . $WeatherData->coord->lon . '"/>'
                                        . '<button type="submit" class="btn btn-block btn-outline-danger text-dark">Remove from favorites</button>'
                                        . '</form></div>';
                                    } else {

                                        echo '<div class="container"><form method="POST" action="/myWeather/Favorites/Add">'
                                        . '<input type="hidden" id="lat" name="FavoriteData[Lat]" value="' . $WeatherData->coord->lat . '">'
                                        . '<input type="hidden" id="lat" name="FavoriteData[Lon]" value="' . $WeatherData->coord->lon . '"/>'
                                        . '<button type="submit" class="btn btn-block btn-outline-success text-dark">Add to favorites</button>'
                                        . '</form></div>';
                                    }
                                    if ($Options['onHome'] == true) {


                                        echo '<div class="container"><form method="POST" action="/myWeather/HomePage">'
                                        . '<input type="hidden" id="lat" name="HomePageData[Lat]" value="' . $WeatherData->coord->lat . '"/>'
                                        . '<input type="hidden" id="lat" name="HomePageData[Lon]" value="' . $WeatherData->coord->lon . '"/>'
                                        . '<input type="hidden" id="lat" name="HomePageData[onHome]" value="0"/>'
                                        . '<button type="submit" class="btn btn-block btn-outline-danger text-dark">Remove from homepage</button>'
                                        . '</form></div>';
                                    } else {

                                        echo '<div class="container"><form method="POST" action="/myWeather/HomePage">'
                                        . '<input type="hidden" id="lat" name="HomePageData[Lat]" value="' . $WeatherData->coord->lat . '">'
                                        . '<input type="hidden" id="lat" name="HomePageData[Lon]" value="' . $WeatherData->coord->lon . '"/>'
                                        . '<input type="hidden" id="lat" name="HomePageData[onHome]" value="1"/>'
                                        . '<button type="submit" class="btn btn-block btn-outline-success text-dark">Add to home page</button>'
                                        . '</form></div>';
                                    }
                                } else {
                                    echo 'Login or signup to save favorite weather <a href="/Login/Signup" class="btn btn-block btn-outline-primary text-dark px-1" role="button">Sign up</a>'
                                    . '<a href="/Login" class="btn btn-block btn-outline-primary text-dark" role="button">Login</a>';
                                }
                                ?>

                            </div>
                            <!--User Controls-->
                        </div>
                        <div class="col-sm">

                            <script>
                                window.onload = function () {

                                    var chart = new CanvasJS.Chart("chartContainer", {
                                        animationEnabled: true,
                                        title: {
                                            text: "Temperature"
                                        },
                                        axisX: {
                                            title: "Time of day"
                                        },
                                        axisY: {

                                            suffix: "oC"
                                        },
                                        data: [{
                                                type: "line",
                                                name: "Hourly temperature",
                                                connectNullData: true,
                                                //nullDataLineDashType: "solid",
                                                xValueType: "dateTime",
                                                xValueFormatString: "DD MMM hh:mm TT",
                                                yValueFormatString: "#,##0.##\"%\"",
                                                dataPoints: [
                                                    {x: 1501048673000, y: 35.939},
                                                    {x: 1501052273000, y: 40.896},
                                                    {x: 1501055873000, y: 56.625},
                                                    {x: 1501059473000, y: 26.003},
                                                    {x: 1501063073000, y: 20.376},
                                                    {x: 1501066673000, y: 19.774},
                                                    {x: 1501070273000, y: 23.508},
                                                    {x: 1501073873000, y: 18.577},
                                                    {x: 1501077473000, y: 15.918},
                                                    {x: 1501081073000, y: null}, // Null Data
                                                    {x: 1501084673000, y: 10.314},
                                                    {x: 1501088273000, y: 10.574},
                                                    {x: 1501091873000, y: 14.422},
                                                    {x: 1501095473000, y: 18.576},
                                                    {x: 1501099073000, y: 22.342},
                                                    {x: 1501102673000, y: 22.836},
                                                    {x: 1501106273000, y: 23.220},
                                                    {x: 1501109873000, y: 23.594},
                                                    {x: 1501113473000, y: 24.596},
                                                    {x: 1501117073000, y: 31.947},
                                                    {x: 1501120673000, y: 31.142}
                                                ]
                                            }]
                                    });
                                    chart.render();

                                }
                            </script>
                            </head>
                            <body>
                                <div id="chartContainer" style="height: 270px; max-width: 420px; margin: 0px auto;"></div>
                                <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

                        </div>
                    </div>
                    <div class="container">
                        <div class="row p-3 mb-3"> 
                            <button class="btn btn-info text-center btn-block" type="button" data-toggle="collapse" data-target="#collapseForecast" aria-expanded="false" aria-controls="collapseForecast">
                                &#8675;  &#8675; Forecast show/hide &#8675;  &#8675;
                            </button>
                        </div>
                        <div class="collapse" id="collapseForecast"> 
                            <?php
                            foreach ($ForecastData->list as $Forcast) {
                                ?>
                                <!--Forecast -->
                                <div class="row border p-1 mb-3 shadow">

                                    <img src="<?php echo 'https://progressiveweatherapp.com/images/weathericons/' . $Forcast->weather[0]->icon . '.png'; ?>" class="float-left">
                                    <div class="col"><h6><?php echo date('l', $Forcast->dt); ?></h6> <?php echo date('H:i', $Forcast->dt); ?></div>
                                    <div class="col">Temp: <?php echo $Forcast->main->temp; ?>℃</div>
                                    <div class="col">Humidity: <?php echo $Forcast->main->humidity; ?></div>
                                    <div class="col">Wind direction: <?php echo $Forcast->wind->deg; ?></div>
                                    <div class="col">Pressure: <?php echo $Forcast->main->pressure; ?></div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            return;
        }

    }
    