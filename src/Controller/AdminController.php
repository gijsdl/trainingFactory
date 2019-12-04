<?php


namespace App\Controller;


use App\Entity\Person;
use App\Entity\Training;
use App\Form\PersonType;
use App\Form\TrainingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home" )
     */
    public function adminHome(){
        return $this->render('admin/home.html.twig');
    }

    /**
     * @Route("/admin/training-overzicht", name="admin_training_overzicht")
     */
    public function trainingOverzicht(){

        $trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();

       return $this->render('admin/training-overzicht.html.twig', ["trainingen"=>$trainingen]);
    }

    /**
     * @Route("/admin/training/toevoegen", name="admin_training_toevoegen")
     */
    public function trainingToevoegen(Request $request){
        $form = $this->createForm(TrainingType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $training = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($training);
            $em->flush();
            $this->addFlash('success', 'Training toegevoegd.');

            return $this->redirectToRoute('admin_training_overzicht');
        }

        return $this->render('admin/training-toevoegen.html.twig',['trainingForm'=>$form->createView()]);
    }

    /**
     * @Route("/admin/training/aanpassen/{id}", name="admin_training_aanpassen")
     */
    public function trainingAanpassen(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $training = $em->getRepository(Training::class)->find($id);

        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $training = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($training);
            $em->flush();
            $this->addFlash('success', 'Training aangepast.');

            return $this->redirectToRoute('admin_training_overzicht');
        }

        return $this->render('admin/training-toevoegen.html.twig',['trainingForm'=>$form->createView()]);

    }

    /**
     * @Route("/admin/training/verwijder/{id}", name="admin_training_verwijder")
     */
    public function trainingVerwijder($id){

        $em = $this->getDoctrine()->getManager();
        $training = $em->getRepository(Training::class)->find($id);

        $em->remove($training);
        $em->flush();
        $this->addFlash('success', 'Training verwijderd.');

        return $this->redirectToRoute('admin_training_overzicht');
    }
}