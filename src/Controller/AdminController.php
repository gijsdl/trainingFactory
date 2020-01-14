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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/home.html.twig');
    }

    /**
     * @Route("/admin/training-overzicht", name="admin_training_overzicht")
     */
    public function trainingOverzicht(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $trainingen = $this->getDoctrine()->getRepository(Training::class)->findAll();

       return $this->render('admin/training-overzicht.html.twig', ["trainingen"=>$trainingen]);
    }

    /**
     * @Route("/admin/training/toevoegen", name="admin_training_toevoegen")
     */
    public function trainingToevoegen(Request $request){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $training = $em->getRepository(Training::class)->find($id);

        $em->remove($training);
        $em->flush();
        $this->addFlash('success', 'Training verwijderd.');

        return $this->redirectToRoute('admin_training_overzicht');
    }

    /**
     * @Route("/admin/leden", name="admin_leden")
     */
    public function leden(){
        $em = $this->getDoctrine()->getManager();
        $leden= $em->getRepository(Person::class)->findAllRole("ROLE_LID");

        return $this->render("admin/leden.html.twig",["leden"=>$leden]);
    }

    /**
     * @Route("/admin/disable/{id}", name="admin_disable_member")
     */
    public function disable($id){
        $em = $this->getDoctrine()->getManager();
        $lid = $em->getRepository(Person::class)->find($id);
        $lid->setEnabled(0);
        $em->flush();
        return $this->redirectToRoute("admin_leden");
    }

    /**
     * @Route("/admin/enable/{id}", name="admin_enable_member")
     */
    public function enable($id){
        $em = $this->getDoctrine()->getManager();
        $lid = $em->getRepository(Person::class)->find($id);
        $lid->setEnabled(1);
        $em->flush();
        return $this->redirectToRoute("admin_leden");
    }

    /**
     * @Route("/admin/lid-les/{id}", name="admin_lid_les")
     */
    public function adminLidLes($id){
        $em = $this->getDoctrine()->getManager();
        $lid = $em->getRepository(Person::class)->find($id);
        $registrations = $lid->getRegistrations();

        return $this->render("admin/lid-les.html.twig", ["registrations"=>$registrations, "lid"=>$lid]);
    }

    /**
     * @Route("/admin/instr-overzicht", name="admin_instr_overzicht")
     */
    public function adminInstrOverzicht(){
        $em = $this->getDoctrine()->getManager();
        $instructors = $em->getRepository(Person::class)->findAllRole("ROLE_INSTR");
        return $this->render("admin/instr-overzicht.html.twig", ["instructors"=>$instructors]);
    }

    /**
     * @Route("/admin/instr-les/{id}", name="admin_instructor_les")
     */
    public function adminInstrLes($id){
        $em = $this->getDoctrine()->getManager();
        $lid = $em->getRepository(Person::class)->find($id);
        $lesssons = $lid->getLessons();

        return $this->render("admin/instr-les.html.twig", ["lessons"=>$lesssons, "lid"=>$lid]);
    }
}