<?php


abstract class BaseController {
   
    
    
    
    protected function getUserUUID()
    {
        
    }
    
    protected function checkLoggedIn()
    {
        
        if(isset($_SESSION['Login']['isLOGGEDIN']) === true)
        {
            return;
        } else {
             $this->ControllerView->
                     PromptWindow('Error', 'Your not logged in...', array('AUTOREDIRECT' => '/Login'));
        }
        
       
    }
    
    
    
}
