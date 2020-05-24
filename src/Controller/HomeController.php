<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->redirectToRoute('log_add');
    }

    /**
     * @Route("/vue", name="vue_entry")
     */
    public function vue()
    {
        return $this->render('home/vue.html.twig');
    }
}
