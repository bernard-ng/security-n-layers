<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class HomeController extends AbstractController
{

    /**
     * @Route(path="", name="app_home", methods={"GET"})
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
}
