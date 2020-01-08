<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Person;
use App\Entity\Registration;
use App\Form\LidType;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
     * @Route("/lid/wijzig/{id}/{password}", name="lid_wijzig")
     */
    public function lidWijzig(Request $request, UserPasswordEncoderInterface $encoder, $id, $password)
    {

        $this->denyAccessUnlessGranted("ROLE_LID");
        $lid = $this->getDoctrine()->getRepository(Person::class)->find($id);


        if ($password == "true") {
            $form = $this->createFormBuilder()
                ->add('wachtwoord', PasswordType::class, ['mapped' => false, 'help' => 'vul uw huidig wachtwoord in', 'label'=>'huidig wachtwoord'])
                ->add('password', PasswordType::class, ['label' => 'wachtwoord'])
            ->getForm();
        } else {
            $form = $this->createForm(LidType::class, $lid);
            $form->remove('username')
                ->remove('password');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ( $encoder->isPasswordValid($this->getUser(), $form->get('wachtwoord')->getData())) {

                if($password == "true") {
                    $lid->setPassword($encoder->encodePassword($lid, $form->get('password')->getData()));
                }else{
                    $lid = $form->getData();
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($lid);
                $em->flush();

                $this->addFlash('success', 'Uw account is gewijzigd.');


                return $this->redirectToRoute('lid_home');
            } else {
                $this->addFlash('danger', 'uw wachtwoord is niet correct.');

            }

        }

        return $this->render('lid/wijzig.html.twig', ['lidForm' => $form->createView()]);
    }

    /**
     * @Route("/lid/lesoverzicht/{date}", name="lid_les_overzicht")
     */
    public function lidLesOverzicht($date)
    {
        $this->denyAccessUnlessGranted("ROLE_LID");
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime($date);
        $lessons = $em->getRepository(Lesson::class)->findByDate($date);

        return $this->render('lid/inschrijf.html.twig', ["lessons" => $lessons]);
    }

    /**
     * @Route("/lid/inschrijven/{id}", name="lid_inschrijven")
     */
    public function lidInschrijven($id)
    {
        $this->denyAccessUnlessGranted("ROLE_LID");
        $em = $this->getDoctrine()->getManager();

        $lesson = $em->getRepository(Lesson::class)->find($id);

        foreach ($lesson->getRegistrations() as $registration) {
            if ($registration->getMemberId() == $this->getUser()) {
                $this->addFlash('danger', 'u heeft zich al ingeschreven.');
                $date = $lesson->getDate()->format('y-m-d');
                return $this->redirectToRoute('lid_les_overzicht', array('date' => $date));
            }
        }

        if ($lesson->getDate()->format('y-m-d') < date('y-m-d')) {
            $this->addFlash('danger', 'deze les is al voorbij');
            $date = date('y-m-d');
            return $this->redirectToRoute('lid_les_overzicht', array('date' => $date));

        } elseif ($lesson->getRegistrations()->count() < $lesson->getMaxPersons()) {
            $registration = new Registration();
            $registration->setLessonId($lesson);
            $registration->setMemberId($this->getUser());
            $em->persist($registration);
            $em->flush();

            $this->addFlash('success', 'u bent aangemeld');
            return $this->redirectToRoute('lid_inschrijf_overzicht');
        } else {
            $this->addFlash('danger', 'deze les is al vol');
            $date = $lesson->getDate()->format('y-m-d');
            return $this->redirectToRoute('lid_les_overzicht', array('date' => $date));
        }

    }

    /**
     * @Route("/lid/inschrijf_overzicht", name="lid_inschrijf_overzicht")
     */
    public function lidInschrijfOverzicht(){
        $this->denyAccessUnlessGranted("ROLE_LID");
        $em = $this->getDoctrine()->getManager();
        $lid = $em->getRepository(Person::class)->find($this->getUser());
        $registrations = $lid->getRegistrations();
        $lessons = array();
        return $this->render('lid/inschrijf-overzicht.html.twig', ["lessons"=> $lessons, "registrations"=>$registrations]);
    }

    /**
     * @Route("/lid/verwijder_registration/{id}", name="lid_registration_verwijder")
     */
    public function verwijderRegistration($id){
        $this->denyAccessUnlessGranted("ROLE_LID");
        $em = $this->getDoctrine()->getManager();
        $registration = $em->getRepository(Registration::class)->find($id);
        $em->remove($registration);
        $em->flush();

        $this->addFlash('success', 'u heeft zich uitgeschreven');
        return $this->redirectToRoute('lid_inschrijf_overzicht');
    }

}