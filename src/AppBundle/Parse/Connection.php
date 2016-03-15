<?php
namespace AppBundle\Parse;

use Parse\ParseClient;
use Parse\ParseSessionStorage;
use Parse\ParseUser;

class Connection
{
    private $appId;
    private $restKey;
    private $masterKey;
    private $url;

    public function __construct($appId, $restKey, $masterKey, $url)
    {
        $this->appId = $appId;
        $this->restKey = $restKey;
        $this->masterKey = $masterKey;
        $this->url = $url;
    }

    public function connect()
    {
        ParseClient::initialize($this->appId, $this->restKey, $this->masterKey);
        ParseClient::setServerURL($this->url);
        ParseClient::setStorage( new ParseSessionStorage() );
    }

    public function isLoggedIn()
    {
        $this->connect();
        $user = ParseUser::getCurrentUser();
        return (!empty($user)) ? 1 : 0;
    }
}
