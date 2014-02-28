<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Form\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class CreateType extends AbstractType
{
    /**
     * @var string
     */
    protected $parentId;

    /**
     * @var array
     */
    protected $templates;

    public function __construct($parentId, $templates)
    {
        $this->parentId  = $parentId;
        $this->templates = $templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'page_create';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Presta\CMSCoreBundle\Doctrine\Phpcr\Page',
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['translation_domain'] = 'PrestaCMSCoreBundle';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parentId', 'hidden', array('mapped' => false, 'data' => $this->parentId))
            ->add(
                'title',
                null,
                array('label' => 'cms_page.form.seo.label.title', 'mapped' => false, 'required' => false)
            )
            ->add(
                'template',
                'choice',
                array(
                    'label'    => 'cms_page.form.page.label.template',
                    'choices'  => $this->templates,
                    'required' => true
                )
            );
    }
}
