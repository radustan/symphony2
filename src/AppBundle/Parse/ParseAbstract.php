<?php
namespace AppBundle\Parse;

use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseSessionStorage;

abstract class ParseAbstract
{
    protected $appId;
    protected $restKey;
    protected $masterKey;
    protected $url;
    protected $object;
    protected $tableName;

    public function __construct($appId, $restKey, $masterKey, $url, $connect = true)
    {
        $status = session_status();
        if($status == PHP_SESSION_NONE) {
            //There is no active session
            session_start();
        }
        $this->appId = $appId;
        $this->restKey = $restKey;
        $this->masterKey = $masterKey;
        $this->url = $url;

        if($connect) {
            ParseClient::initialize($this->appId, $this->restKey, $this->masterKey);
            ParseClient::setServerURL($this->url);
            ParseClient::setStorage( new ParseSessionStorage() );
        }
    }

    public function create()
    {
        $this->object = new ParseObject($this->tableName);
        return $this;
    }

    public function set($field, $value) {
        $this->object->set($field, $value);
        return $this;
    }

    public function getAll()
    {
        $result = array();
        $query = new ParseQuery($this->tableName);
        try {
            $result = $query->find();
            // The object was retrieved successfully.
        } catch (ParseException $ex) {
            // The object was not retrieved successfully.
            // error is a ParseException with an error code and message.
        }

        return $result;
    }

    public function save()
    {
        try {
            $this->object->save();
            return $this->object->getObjectId();
        } catch (ParseException $e) {
            return null;
        }
    }
}
