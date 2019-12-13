<?php


namespace App\Controller;


use App\Entity\Person;
use App\Form\LidType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LidController extends AbstractController
{
    /**
     * @Route("/lid/home", name="lid_home")
     */
    public function lidHome()
    {
        $this->denyAccessUnlessGranted("ROLE_LID");
        return $this->render("lid/home.html.twig");
    }

    /**
     * @Route("/lid/wijzig/{id}", name="lid_wijzig")
     */
    public function lidWijzig(Request $request, UserPasswordEncoderInterface $encoder, $id)
    {
        $lid = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $form = $this->createForm(LidType::class, $lid);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lid = $form->getData();
            $lid->setRoles(array('ROLE_LID'));
            $lid->setPassword($encoder->encodePassword($lid, $lid->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($lid);
            $em->flush();

            $this->addFlash('success', 'Uw account is gewijzigd.');

            return $this->redirectToRoute('lid_home');
        }

        return $this->render('bezoeker/registratie.hml.twig', ['lidForm' => $form->createView()]);
    }

}