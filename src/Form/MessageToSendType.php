<?php

namespace App\Form;

use App\Entity\MessageSender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class MessageToSendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('last_name', TextType::class, ['label' => 'Nom :', 'required' => 'true', 'attr' => ['placeholder' => 'Nom'],])
            ->add('first_name', TextType::class, ['label' => 'Prénom :', 'required' => 'true', 'attr' => ['placeholder' => 'Prénom'],])
            ->add('email', EmailType::class, ['label' => 'Email :', 'required' => 'true', 'attr' => ['placeholder' => 'Email'],])
            ->add('zip_code', TextType::class, ['label' => 'Code postal :', 'required' => 'true', 'attr' => ['placeholder' => 'Code postal'],])
            ->add('city', TextType::class, ['label' => 'Commune :', 'required' => 'true', 'attr' => ['placeholder' => 'Ville'],])
            ->add('message', TextType::class, ['label' => 'Votre message :', 'required' => 'true', 'attr' => ['placeholder' => 'Votre message'],])
            ->add('envoyer', SubmitType::class, ['label' => 'Envoyer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MessageSender::class,
        ]);
    }
}
