<?php

namespace OC\PlatformBundle\Form;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Category;
use OC\PlatformBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = 'D%';

        $builder
            ->add('date', DateType::class)
            ->add('title', TextType::class)
            ->add('content', CkeditorType::class)
            ->add('author', TextType::class)
            ->add('email', TextType::class)
            ->add('image', ImageType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (CategoryRepository $repository) use ($pattern) {
                    return $repository->getLikeQueryBuilder($pattern);
                },
            ])
            ->add('save', SubmitType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $advert = $event->getData();

            if (is_null($advert)) {
                return;
            }

            if (!$advert->getPublished()) {
                $event->getForm()->add('published', CheckboxType::class, ['required' => false]);
            } else {
                $event->getForm()->remove('published');
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
