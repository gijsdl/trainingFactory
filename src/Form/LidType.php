<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = date('Y-m-d H:i:s');
        $builder
            ->add('username', null, ['label' => 'gebruikersnaam'])
//        ->add('agreeTerms', CheckboxType::class, [
//            'mapped' => false,
//            'constraints' => [
//                new IsTrue([
//                    'message' => 'You should agree to our terms.',
//                ]),
//            ],
//        ])
            ->add('wachtwoord', PasswordType::class, ['mapped' => false, 'help' => 'vul uw huidig wachtwoord in', 'label'=>'huidig wachtwoord'])
            ->add('password', PasswordType::class, ['label' => 'wachtwoord'])
            ->add('first_name', null, ['label' => 'voornaam'])
            ->add('preprovision', null, ['label' => 'tussenvoegsel'])
            ->add('last_name', null, ['label' => 'achternaam'])
            ->add('date_of_birth', DateType::class, ['years' => range(1950, $date), 'format' => 'dd MM yyyy', 'label' => 'geboortedatum'])
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