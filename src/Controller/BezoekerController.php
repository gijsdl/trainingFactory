<?php


namespace App\Controller;


use App\Entity\Training;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('bezoeker/home.html.twig');
    }

    /**
     * @Route("/training-aanbod", name="training_aanbod")
     */
    public function trainingAanbod()
    {
        $trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();

        return $this->render('bezoeker/training-aanbod.html.twig',['trainingen'=>$trainingen]);
    }

    /**
     * @Route("/gedragsregels", name="gedragsregels")
     */
    public function gedragregels(){
        return $this->render('bezoeker/gedragsregels.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(){
        return $this->render('bezoeker/contact.html.twig');
    }

}