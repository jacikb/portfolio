<?php
/**
 * Created by PhpStorm.
 * User: Jacik2
 * Date: 2018-09-06
 * Time: 16:47
 */

namespace AppBundle\Form;

use AppBundle\Entity\Article;
use AppBundle\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, ["label" => "Tytuł"])
            ->add('section', EntityType::class, array(
                'class' => Section::class,
                'choice_label' => 'name',
            ))
            ->add('content', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                ),
            ))
            ->add("link", TextType::class, ["label" => "Link"])
            ->add("file", TextType::class, ["label" => "Plik"])

            ->add("submit", SubmitType::class, ["label" => "Zapisz"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class" => Article::class]);
    }

}