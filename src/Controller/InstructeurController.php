<?php


namespace App\Controller;


use App\Entity\Lesson;
use App\Entity\Person;
use App\Entity\Registration;
use App\Form\InstrType;
use App\Form\LesType;
use App\Form\LidType;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $lessons = $this->getDoctrine()->getRepository(Lesson::class)->findByBiggerDate(new \DateTime(date('y-m-d')), $this->getUser()->getID());

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

    /**
     * @Route("/instr/deelnemerslijst/{id}", name="instr_deelnemerslijst")
     */
    public function instrDeelnemerslijst($id){
        $this->denyAccessUnlessGranted("ROLE_INSTR");
        $em = $this->getDoctrine()->getManager();
        $lesson = $em->getRepository(Lesson::class)->find($id);
        $registrations = $lesson->getRegistrations();
        return $this->render("instructeur/deelnemer.html.twig", ["registrations"=>$registrations, "les"=>$id]);
    }

    /**
     * @Route("/instr/betalen/{id}/{lesId}", name="instr_betalen")
     */
    public function instrBetalen($id, $lesId){


        $em = $this->getDoctrine()->getManager();
        $registration = $em->getRepository(Registration::class)->find($id);
        $registration->setPayment(true);
        $em->flush();


        return $this->redirectToRoute('instr_deelnemerslijst',array('id'=>$lesId));
    }

    /**
     * @Route("/instr/wijzig/{password}", name="instr_wijzig")
     */
    public function lidWijzig(Request $request, UserPasswordEncoderInterface $encoder, $password)
    {

        $this->denyAccessUnlessGranted("ROLE_INSTR");
        $lid = $this->getDoctrine()->getRepository(Person::class)->find($this->getUser()->getId());


        if ($password == "true") {
            $form = $this->createFormBuilder()
                ->add('wachtwoord', PasswordType::class, ['mapped' => false, 'help' => 'vul uw huidig wachtwoord in', 'label'=>'huidig wachtwoord'])
                ->add('password', PasswordType::class, ['label' => 'wachtwoord'])
                ->add('password', RepeatedType::class, [
                    'type'=> PasswordType::class,
                    'first_options'  => ['label' => 'wachtwoord'],
                    'second_options' => ['label' => 'herhaal wachtwoord'],
                ])
                ->getForm();
        } else {
            $form = $this->createForm(InstrType::class, $lid);
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


                return $this->redirectToRoute('instr_home');
            } else {
                $this->addFlash('danger', 'uw wachtwoord is niet correct.');

            }

        }

        return $this->render('instructeur/wijzig.html.twig', ['instrForm' => $form->createView()]);
    }
}