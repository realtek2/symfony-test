<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('last_name')
            ->add('age')
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('u')
                            ->where('u.id > :id')
                            ->setParameter('id', 0);
                },
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('company', EntityType::class, [
                 'class' => Company::class,
                 'choice_label' => 'name'
                 ])
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'attr' => ['class' => 'form-horizontal', 'required' => true]
        ]);
    }
}
