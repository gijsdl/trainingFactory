<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Person;
use App\Entity\Registration;
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

        $this->denyAccessUnlessGranted("ROLE_LID");
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
            return $this->redirectToRoute('lid_home');
        } else {
            $this->addFlash('danger', 'deze les is al vol');
            $date = $lesson->getDate()->format('y-m-d');
            return $this->redirectToRoute('lid_les_overzicht', array('date' => $date));
        }

    }

}