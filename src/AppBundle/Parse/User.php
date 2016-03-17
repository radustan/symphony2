<?php
namespace AppBundle\Parse;

use Parse\ParseException;
use Parse\ParseUser;

class User extends ParseAbstract
{
    
    public function isLoggedIn()
    {
        try {
            $currentUser = ParseUser::getCurrentUser();
            if ($currentUser)
                return true;
            else
                return false;
        } catch (ParseException $error) {
            return false;
        }
    }

    public function getNavMessage()
    {
        try {
            $currentUser = ParseUser::getCurrentUser();
            if ($currentUser)
                return 'Hi, ' . $currentUser->getUsername();
            else
                return '';
        } catch (ParseException $error) {
            return '';
        }
    }
    
    
}
