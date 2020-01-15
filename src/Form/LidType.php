<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = date('Y');
        $datePast = date('Y', strtotime('-80 years'));
        $builder
            ->add('username', null, ['label' => 'gebruikersnaam'])
            ->add('wachtwoord', PasswordType::class, ['mapped' => false, 'help' => 'vul uw huidig wachtwoord in', 'label'=>'huidig wachtwoord'])
            ->add('password', PasswordType::class, ['label' => 'wachtwoord'])
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options'  => ['label' => 'wachtwoord'],
                'second_options' => ['label' => 'herhaal wachtwoord'],
            ])
            ->add('first_name', null, ['label' => 'voornaam', 'attr'=>['class'=>'text-capitalize']])
            ->add('preprovision', null, ['label' => 'tussenvoegsel'])
            ->add('last_name', null, ['label' => 'achternaam', 'attr'=>['class'=>'text-capitalize']])
            ->add('date_of_birth', DateType::class, ['years' => range($datePast, $date), 'format' => 'dd MM yyyy', 'label' => 'geboortedatum'])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Man' => 'Man',
                    'Vrouw' => 'Vrouw',
                    'Anders' => 'Anders']
                , 'label' => 'geslacht'])
            ->add('email_adress', EmailType::class, ['label' => 'email adres'])
            ->add('street', null, ['label' => 'adres'])
            ->add('postal_code', null, ['label' => 'postcode'])
            ->add('place', null, ['label' => 'plaatsnaam']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\Person']);
    }
}