<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Form\Type;

use Sententiaregum\Bridge\User\DTO\CreateUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type containing properties to create a user
 */
class CreateType extends AbstractType
{
    /**
     * Creates the form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('password', 'password')
            ->add('password_confirm', 'password', ['mapped' => false])
            ->add('email', 'email')
            ->add('email_confirm', 'email', ['mapped' => false])
            ->add('realname', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sen_user_form_create';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => CreateUserDTO::class,
            'intention'       => 'create_account',
            'csrf_protection' => true
        ]);
    }
}
