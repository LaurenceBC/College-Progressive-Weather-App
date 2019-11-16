<?php

class SharedView {

    public function PromptWindow($Title, $Message, $Options = array()) {
        ?>

        <div class="container">
            <div class="card border-info shadow">
                <div class="card-header"><?php echo $Title ?></div>
                <div class="card-body">
                    <?php echo $Message ?>
                    <?php
                    if (array_key_exists('OK', $Options)) {
                        echo '<a href="' . $Options['OK'] . '" class="btn btn-success" role="button" aria-pressed="true">OK</a>';
                    }
                    ?>
                    <?php
                    if (array_key_exists('CANCEL', $Options)) {
                        echo '<a href="' . $Options['CANCEL'] . '" class="btn btn-success" role="button" aria-pressed="true">OK</a>';
                    }
                    ?>
                    <?php
                    if (array_key_exists('AUTOREDIRECT', $Options)) {
                        //echo java script to auto redirect
                        ?>
                        <script type="text/JavaScript">
                            setTimeout("location.href = ' <?php echo $Options['AUTOREDIRECT'] ?>';",<?php echo $Options['AUTOTIME'] ?? 6 ?>);
                        </script>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
        <?php
        return;
    }

    public function showWeatherSearch() {
        ?>

        <div class="container border ml-auto bg-light rounded shadow mb-3">
              <div class="p-3 text-center">
                  <h3>Search for weather.</h3>
            </div>
            <div class="container">
                <div class="search-box input-group">
                    <div class="input-group-prepend">
                        <button class="btn dropdown-toggle" id="searchbarterm" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Search by...</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Name</a>
                            <a class="dropdown-item" href="#">PostCode</a>
                            <a class="dropdown-item" href="#">GeoCoords</a>
                        </div>
                    </div>
                    <input type="text" class="form-control" aria-label="Text input with dropdown button" id="searchbarquery" placeholder="example, Taunton or Taunton,US for non UK location">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="button" onclick="searchWeather()">Search</button>
                    </div>  
                </div>
            </div>
             <div class="p-3 text-center">
               Or search by 
            </div>
            <div class="text-center mb-3">
               <button class="btn btn-outline-info text-dark" type="button" onclick="getLocation()">My Location</button>
            </div>
            
        </div>
        <script>
            $(function () {
                $(".search-box .dropdown-menu a").click(function ()
                {
                    var searchtermtext = $(this).html();
                    $(this).parents().find('button.dropdown-toggle').html(searchtermtext);
                });
            });
        </script>
        <script>
            function searchWeather()
            {
                var searchTerm = document.getElementById("searchbarterm").innerHTML;
                var searchQuery = document.getElementById("searchbarquery").value;

                if(searchTerm === "Search by...")
                {searchTerm = "Name"                       
                }

                location.href = "/Weather/" + searchTerm + "/" + searchQuery;
            }
        </script>

        <script type="text/javascript">
            function getLocation()
            {
                if (navigator.geolocation)
                {
                    navigator.geolocation.getCurrentPosition(onLocationSuccess, onLocationError);
                } else {
                    alert("Error");
                }
            }
            function onLocationSuccess(event)
            {
                var Lat = event.coords.latitude;
                var Lon = event.coords.longitude;
                  location.href = "/Weather/GeoCoords/" + Lat + "/" + Lon;

            }
            function onLocationError(event)
            {
                alert("Error " + event.code + ". " + event.message);
            }
        </script>


        <?php
    }

}
