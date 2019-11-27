<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(){
        return $this->render('bezoeker/home.html.twig');
    }
}