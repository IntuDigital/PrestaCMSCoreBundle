<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;

/**
 * Simple block with a title and content
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class SimpleBlockService extends BaseModelBlockService
{
    /**
     * @var string
     */
    protected $template = 'PrestaCMSCoreBundle:Block:block_simple.html.twig';

    /**
     * {@inheritdoc}
     */
    protected function getContentModelFields()
    {
        return array('link_destination' => 'presta_cms.admin.page');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'title'             => $this->trans('block.default.title'),
            'content'           => $this->trans('block.default.content'),
            'link_label'        => null,
            'link_destination'  => null
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormSettings(FormMapper $formMapper, BlockInterface $block)
    {
        return array(
            array('title', 'text', array('required' => false, 'label' => $this->trans('form.label_title'))),
            array(
                'content',
                'textarea',
                array('attr' => array('class' => 'wysiwyg'), 'label' => $this->trans('form.label_content'))
            ),
            array('link_label', 'text', array('required' => false, 'label' => $this->trans('form.label_link_label')))
        );
    }
}
