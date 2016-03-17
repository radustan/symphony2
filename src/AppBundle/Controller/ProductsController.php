<?php

namespace AppBundle\Controller;
use AppBundle\Parse\Products;
use AppBundle\Parse\User;
use Parse\ParseFile;
use Parse\ParseGeoPoint;
use Parse\ParseUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProductsController extends AbstractController implements LoggedInInterface
{

    /** @Route("/products", name="products") */
    public function indexAction()
    {
        /** @var Products $product */
        $product = $this->parseFactory('Products');
        $products = $product->getAll();
        $headers = array('Name', 'Price', 'Image', 'Location', 'Date created');
        return $this->render(
            'products/index.html.twig',
            array(
                'products' => $products,
                'headers' => $headers
            )
        );
    }

    /** @Route("/products/add", name="products.add") */
    public function addAction()
    {
        $response = new JsonResponse();
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->get('request');
        if ($request->getMethod() == $request::METHOD_POST) {
            $name = $request->get('name');
            $price = $request->get('price');
            /** @var UploadedFile $img */
            $img = $request->files->get('image');
            /** @var Products $product */
            $product = $this->parseFactory('Products');
            $point = new ParseGeoPoint($request->get('latitude', 0), $request->get('longitude', 0));
            $res = $product->create()
                ->setName($name)
                ->setPrice((float)$price)
                ->setImage($img)
                ->set('location', $point)
                ->setCreatedBy()
                ->save();

            if ($res) {
                $response->setData(array(
                    'res' => $res,
                    'success' => true
                ));
                $this->addFlash(
                    'notice',
                    'Product created'
                );
            } else {
                $response->setData(array(
                    'res' => $res,
                    'success' => false
                ));
                $this->addFlash(
                    'notice',
                    'Product not created'
                );
            }

            return $response;

        }
        $response->setData(array(
            'success' => false
        ));
        return $response;
    }
}
