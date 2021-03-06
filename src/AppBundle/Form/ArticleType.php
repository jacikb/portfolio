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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod(Request::METHOD_POST)
            ->add("title", TextType::class, ["label" => "Tytuł"])
            ->add('section', EntityType::class, array(
                'class' => Section::class,
                'choice_label' => 'name',
                'label' => 'Dział'
            ))
            ->add('status', ChoiceType::class, array(
                'choices'  => array(
                    'Public' => Article::STATUS_PUBLIC,
                    'Private' => Article::STATUS_PRIVATE,
                ),
                'label' => 'Status'
            ))
            ->add('content', CKEditorType::class, array(
                'config' => array(
                    'toolbar' => 'standard',
                    'uiColor' => '#f5f5f5',

                ),
                'label' => 'Treść'
            ))
            ->add("pdf", CheckboxType::class, ["label" => "Export to PDF"])
            ->add("link", TextType::class, ["label" => "Link"])
            ->add("file", TextType::class, ["label" => "Plik"])

            ->add("submit", SubmitType::class, ["label" => "Zapisz", "attr" => ["class" => "pull-right"]]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(["data_class" => Article::class]);
    }
}