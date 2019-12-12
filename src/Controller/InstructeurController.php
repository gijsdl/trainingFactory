<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Form\LesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InstructeurController extends AbstractController
{
    /**
     * @Route("/instr/home", name="instr_home")
     */
    public function instrHome()
    {
        $this->denyAccessUnlessGranted('ROLE_INSTR');
        return $this->render('instructeur/home.html.twig');
    }

    /**
     * @Route("/instr/les_plan", name="instr_les_plan")
     */
    public function instrPlanLes(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_INSTR');
        $form = $this->createForm(LesType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $training = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($training);
            $em->flush();
            $this->addFlash('success', 'les toegevoegd.');

            return $this->redirectToRoute('instr_les_overzicht');
        }

        return $this->render('instructeur/les-plan.html.twig', ['lesForm' => $form->createView()]);
    }

    /**
     * @Route("/instr/les_overzicht", name="instr_les_overzicht")
     */
    public function instrLesOverzicht(){
        $this->denyAccessUnlessGranted('ROLE_INSTR');
        $lessons = $this->getDoctrine()->getRepository(Lesson::class)->findAll();

        return $this->render('instructeur/les-overzicht.html.twig', ["lessons"=>$lessons]);

    }

    /**
     * @Route("/instr/les_wijzig/{id}", name="instr_les_wijzig")
     */
    public function instrLesWijzig(Request $request, $id){
        $this->denyAccessUnlessGranted('ROLE_INSTR');
        $lesson = $this->getDoctrine()->getRepository(Lesson::class)->find($id);
        $form = $this->createForm(LesType::class, $lesson);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $training = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($training);
            $em->flush();
            $this->addFlash('success', 'les is gewijzigd.');

            return $this->redirectToRoute('instr_les_overzicht');
        }

        return $this->render('instructeur/les-plan.html.twig', ['lesForm' => $form->createView()]);
    }

    /**
     * @Route("/instr/les_verwijder/{id}", name="instr_les_verwijder")
     */
    public function instrLesVerwijder($id){
        $this->denyAccessUnlessGranted('ROLE_INSTR');
        $em = $this->getDoctrine()->getManager();
        $lesson = $em->getRepository(Lesson::class)->find($id);

        $em->remove($lesson);
        $em->flush();
        $this->addFlash('success', 'Training verwijderd.');

        return $this->redirectToRoute('instr_les_overzicht');
    }

}