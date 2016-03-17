<?php
namespace AppBundle\Parse;

use Parse\ParseException;
use Parse\ParseFile;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Products extends ParseAbstract
{
    protected $tableName = 'Products';
    /** @var  ParseObject $object */
    protected $object;

    public function setName($value)
    {
        $this->object->set('name', $value);
        return $this;
    }

    public function setPrice($value)
    {
        $this->object->set('price', $value);
        return $this;
    }

    public function setImage(UploadedFile $img) {
        $localFilePath = $img->getRealPath();
        $file = ParseFile::createFromFile($localFilePath, $img->getClientOriginalName());
        $file->save();
        $this->object->set('image', $file);
        return $this;
    }

    public function setCreatedBy()
    {
        $this->object->set('createdby', ParseUser::getCurrentUser());
        return $this;
    }

    public function getAll()
    {
        $result = array();
        $query = new ParseQuery($this->tableName);
        $query->equalTo("createdby", ParseUser::getCurrentUser());
        try {
            $result = $query->find();
            // The object was retrieved successfully.
        } catch (ParseException $ex) {
            // The object was not retrieved successfully.
            // error is a ParseException with an error code and message.
        }

        return $result;
    }

}
