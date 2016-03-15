<?php
namespace AppBundle\Controller;
use AppBundle\AppBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use AppBundle\Parse\Connection;
use AppBundle\Parse\Test;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractController extends Controller
{
    public function parseConnect()
    {
        /** @var Connection $parseConnection */
        $parseConnection = $this->get('parse.connection');
        $parseConnection->connect();
    }
}
