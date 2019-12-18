<?php


namespace App\Controller;


use App\Entity\Training;
use App\Form\LidType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

        return $this->render('bezoeker/training-aanbod.html.twig', ['trainingen' => $trainingen]);
    }

    /**
     * @Route("/gedragsregels", name="gedragsregels")
     */
    public function gedragregels()
    {
        return $this->render('bezoeker/gedragsregels.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('bezoeker/contact.html.twig');
    }

    /**
     * @Route("/registratie", name="registratie")
     */
    public function registratie(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(LidType::class);
        $form->remove('wachtwoord');

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            $lid =$form->getData();
            $lid->setRoles(array('ROLE_LID'));
            $lid->setPassword($encoder->encodePassword($lid, $lid->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($lid);
            $em->flush();

            $this->addFlash('success', 'Uw account is aangemaakt. U kunt nu inloggen.');

            return $this->redirectToRoute('home');
        }

        return $this->render('bezoeker/registratie.hml.twig', ['lidForm'=>$form->createView()]);
    }

}