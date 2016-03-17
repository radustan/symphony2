<?php
namespace AppBundle\Parse;

use Parse\ParseClient;
use Parse\ParseSessionStorage;
use Parse\ParseUser;

class Connection extends ParseAbstract
{
    public function connect()
    {
        ParseClient::initialize($this->appId, $this->restKey, $this->masterKey);
        ParseClient::setServerURL($this->url);
        ParseClient::setStorage( new ParseSessionStorage() );
    }
}
