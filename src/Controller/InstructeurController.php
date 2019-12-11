<?php


namespace App\Controller;


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
     * @Route("/instr/plan_les", name="instr_plan_les")
     */
    public function instrPlanLes(Request $request)
    {
        $form = $this->createForm(LesType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $training = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($training);
            $em->flush();
            $this->addFlash('success', 'les toegevoegd.');

            return $this->redirectToRoute('instr_home');
        }

        return $this->render('instructeur/plan-les.html.twig', ['lesForm' => $form->createView()]);
    }
}