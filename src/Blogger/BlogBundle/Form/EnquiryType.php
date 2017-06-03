<?php
// src/Blogger/BlogBundle/Form/EnquiryType.php

namespace Blogger\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('email', EmailType::class, array('label' => 'Correu', 'required' => false));
        $builder->add('subject', TextType::class, array('label' => 'Tema'));
        $builder->add('body', TextareaType::class, array('label' => 'Missatge'));
        $builder->add('save', SubmitType::class, array('label' => 'Enviar missatge'));
    }

    public function getName()
    {
        return contact;
    }
}