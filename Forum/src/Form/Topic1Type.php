<?php

namespace App\Form;

use App\Entity\Topic;
use App\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Topic1Type extends AbstractType
{
    private $em;
    private $sections;
    private $sectionsName;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->sections = $em->getRepository(Section::class)->findAll();
        foreach($this->sections as $section){
            $name = $section->getName();
            $this->sectionsName[$name] = $section;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Название',
				'attr' => ['class' => 'form-control'],
                'constraints' =>	[
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Название должно быть больше {{ limit }} символов',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                            ])
                ],
			])
            ->add('section', ChoiceType::class, [
                'label' => 'Раздел',
                'choices' => $this->sectionsName,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('content', TextareaType::class,[
                'label' => 'Описание',
				'attr' => ['class' => 'form-control'],
                'constraints' =>	[
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Описание должно быть больше {{ limit }} символов',
                        // max length allowed by Symfony for security reasons
                        'max' => 15000,
                            ])
                ],
			])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
