<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{
 /**
 * @Route("/", name="home")
 */
  public function index(): Response
  {
    return $this->render('list/index.html.twig', [
      'controller_name' => 'ListController',
    ]);
  }
}
