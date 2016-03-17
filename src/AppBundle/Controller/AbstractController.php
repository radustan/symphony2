<?php
namespace AppBundle\Controller;

use AppBundle\Parse\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractController extends Controller
{
    public function parseConnect()
    {
        /** @var Connection $parseConnection */
        $parseConnection = $this->get('parse.connection');
        $parseConnection->connect();
    }

    public function parseFactory($className)
    {
        return $this->get('parse.'.$className);
    }
}
