<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner un nom',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit faire au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Le nom doit faire au plus {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1140
                ],
                'required' => false,
                'label' => 'Temps de préparation (en min)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'Le temps de préparation doit être positif',
                    ]),
                    new Assert\LessThan([
                        'value' => 1001,
                        'message' => 'Le temps de préparation doit être inférieur à {{ compared_value }}',
                    ]),
                ],
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 50
                ],
                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'Le nombre de personnes doit être positif',
                    ]),
                    new Assert\LessThan([
                        'value' => 51,
                        'message' => 'Le nombre de personnes doit être inférieur à {{ compared_value }}',
                    ]),
                ],
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5
                ],
                'required' => false,
                'label' => 'Difficulté (1 à 5)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'La difficulté doit être positive',
                    ]),
                    new Assert\LessThan([
                        'value' => 6,
                        'message' => 'La difficulté doit être inférieure à {{ compared_value }}',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 500
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner une description',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'La description doit faire au moins {{ limit }} caractères',
                        'max' => 500,
                        'maxMessage' => 'La description doit faire au plus {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 1000
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false,
                'currency' => 'EUR',
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'Le prix doit être positif',
                    ]),
                    new Assert\LessThan([
                        'value' => 1001,
                        'message' => 'Le prix doit être inférieur à {{ compared_value }}',
                    ]),
                ],
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input mt-4',
                ],
                'label' => 'Favori',
                'label_attr' => [
                    'class' => 'form-check-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ]
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'attr' => [
                    'class' => 'form-select d-flex flex-column',
                ],
                'query_builder' => function (IngredientRepository $ing) {
                    return $ing->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Veuillez sélectionner au moins un ingrédient',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Enregistrer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
