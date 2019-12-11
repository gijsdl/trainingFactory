<?php


namespace App\Form;


use App\Entity\Lesson;
use App\Entity\Person;
use App\Entity\Training;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LesType extends AbstractType
{
    private $personRepository;
    public function __construct(PersonRepository $personRepository)
    {
    $this->personRepository = $personRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('time',null, ['label'=>'Tijd'])
            ->add('date', null, ['label'=>'datum', 'format' => 'dd MM yyyy'])
            ->add('location', null, ['label'=>'locatie'])
            ->add('max_persons', null, ['label'=>'max personen'])
            ->add('training_id', EntityType::class, [
                'class' => Training::class,
                'choice_label' => 'name',
                'label'=>'training'])
            ->add('instructor_id', EntityType::class, [
                'class' => Person::class,
                'choice_label' => 'first_name',
                'choices' => $this->personRepository->findAllInstuctors(),
                'label'=>'instructeur'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Lesson'
        ]);
    }

}