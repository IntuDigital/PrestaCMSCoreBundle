<?php

use Symfony\Cmf\Component\Testing\HttpKernel\TestKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * AppKernel for PrestaCMSCoreBundle Functional tests
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class AppKernel extends TestKernel
{
    public function configure()
    {
        $this->requireBundleSets(array('default','phpcr_odm', 'sonata_admin'));

        $this->addBundles(
            array(
                new \Sonata\SeoBundle\SonataSeoBundle(),

                // CMF bundles
                new \Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
                new \Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
                new \Symfony\Cmf\Bundle\MenuBundle\CmfMenuBundle(),
                new \Symfony\Cmf\Bundle\ContentBundle\CmfContentBundle(),
                //new \Symfony\Cmf\Bundle\TreeBrowserBundle\CmfTreeBrowserBundle(),
                new \Symfony\Cmf\Bundle\BlockBundle\CmfBlockBundle(),

                new \Presta\CMSCoreBundle\PrestaCMSCoreBundle(),
            )
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.php');
    }
}
