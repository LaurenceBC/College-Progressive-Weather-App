<?php

/**
 * View class for Home
 */
class HomeView extends \SharedView
{

    public function Home($Options = array())
    {

        ?>
        <div class="container text-center">
            <?php
                    //Show search box
                    $this->showWeatherSearch();
                    ?>
        </div>

<?php }
}
