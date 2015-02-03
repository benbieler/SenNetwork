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

use Sententiaregum\Bridge\User\DTO\AuthDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Simple form type for a registration form
 */
class AuthType extends AbstractType
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
            ->add('password', 'password');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sen_user_form_auth';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => AuthDTO::class,
            'intention'       => 'request_api_key',
            'csrf_protection' => true
        ]);
    }
}
