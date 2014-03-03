<?php
/**
 * This file is part of the PrestaCMSCoreBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\Collection;
use Knp\Menu\NodeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Route;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class Page extends AbstractParentModel
{
    const STATUS_DRAFT      = 'draft';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVE    = 'archive';

    /**
     * @var string
     */
    protected $title;

    /**
     * This is not store in database, it's used to pass data form the form to the route
     * @var string
     */
    protected $urlRelative;

    /**
     * This is not store in database, it's used to pass data form the form to the route
     * @var string
     */
    protected $pathComplete;

    /**
     * This is not store in database, it's used to pass data form the form to the route
     * @var string
     */
    protected $urlComplete;

    /**
     * @var boolean $urlCompleteMode
     */
    protected $urlCompleteMode;

    /**
     * @var string $metaKeywords
     */
    protected $metaKeywords;

    /**
     * @var string $metaDescription
     */
    protected $metaDescription;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $status
     */
    protected $status = self::STATUS_PUBLISHED;

    /**
     *  @var string
     */
    protected $locale;

    /**
     * @var string $template
     */
    protected $template;

    /**
     * @var RouteObjectInterface[]
     */
    protected $routes;

    /**
     * MenuNode[]
     */
    protected $menuNodes;

    /**
     * @var Date
     */
    protected $lastCacheModifiedDate;

    /**
     * @var bool
     */
    protected $cachePrivate = false;

    /**
     * @var int
     */
    protected $cacheMaxAge = 0;

    /**
     * @var int
     */
    protected $cacheSharedMaxAge = 0;

    /**
     * @var bool
     */
    protected $cacheMustRevalidate = false;

    /**
     * @var string
     */
    protected $descriptionTitle;

    /**
     * @var string
     */
    protected $descriptionContent;

    /**
     * @var bool
     */
    protected $descriptionEnabled = false;

    /**
     * @var int
     */
    protected $descriptionMediaId;

    /**
     * @var Media
     */
    protected $descriptionMedia;

    public function __construct()
    {
        parent::__construct();

        $this->lastCacheModifiedDate = new \DateTime();

        $this->routes = new ArrayCollection();
        $this->menus  = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * As page can have different types of children, we filter on page
     *
     * This is used in forms
     *
     * @return Collection
     */
    public function getChildren()
    {
        if (count($this->children) == 0) {
            return $this->children;
        }

        return $this->children->filter(
            function ($e) {
                return $e instanceof Page;
            }
        );
    }

    /**
     * Add a zone
     *
     * @param Zone $zone
     */
    public function addZone(Zone $zone)
    {
        $this->children->set($zone->getName(), $zone);
    }

    /**
     * @param Zone $zone
     *
     * @return bool
     */
    public function hasZone(Zone $zone)
    {
        return $this->children->containsKey($zone->getName());
    }

    /**
     * @return Collection
     */
    public function getZones()
    {
        if (count($this->children) == 0) {
            return $this->children;
        }

        return $this->children->filter(
            function ($e) {
                return $e instanceof Zone;
            }
        );
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Return Children page description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Page
     */
    public function getRouteContent()
    {
        return $this;
    }

    /**
     * @param string $urlRelative
     */
    public function setUrlRelative($urlRelative)
    {
        if (strpos($urlRelative, '/') !== 0) {
            $urlRelative = '/' . $urlRelative;
        }
        $this->urlRelative = rtrim($urlRelative, '/');
    }

    /**
     * @return string
     */
    public function getPathComplete()
    {
        return (string) $this->pathComplete;
    }

    /**
     * @param string $pathComplete
     */
    public function setPathComplete($pathComplete)
    {
        if (strpos($pathComplete, '/') !== 0) {
            $pathComplete = '/' . $pathComplete;
        }
        $this->pathComplete = $pathComplete;
    }

    /**
     * @return string
     */
    public function getUrlRelative()
    {
        return (string) $this->urlRelative;
    }

    /**
     * @param string $urlComplete
     */
    public function setUrlComplete($urlComplete)
    {
        if (strpos($urlComplete, '/') !== 0) {
            $urlComplete = '/' . $urlComplete;
        }
        $this->urlComplete = rtrim($urlComplete, '/');
    }

    /**
     * @return string
     */
    public function getUrlComplete()
    {
        return (string) $this->urlComplete;
    }

    /**
     * @param boolean $urlCompleteMode
     */
    public function setUrlCompleteMode($isUrlCompleteMode)
    {
        $this->urlCompleteMode = $isUrlCompleteMode;
    }

    /**
     * @return boolean
     */
    public function isUrlCompleteMode()
    {
        return (bool) $this->urlCompleteMode;
    }

    /**
     * Check is page has routing data, used when update the routes in EventListener
     *
     * @return boolean
     */
    public function hasRoutingData()
    {
        return (isset($this->urlComplete) || isset($this->urlRelative));
    }

    /**
     * @param Date $lastCacheModifiedDate
     */
    public function setLastCacheModifiedDate($lastCacheModifiedDate)
    {
        $this->lastCacheModifiedDate = $lastCacheModifiedDate;
    }

    /**
     * @return Date
     */
    public function getLastCacheModifiedDate()
    {
        return $this->lastCacheModifiedDate;
    }

    /**
     * @param Route $route
     */
    public function addRoute($route)
    {
        $this->routes->add($route);
    }

    /**
     * @param Route $route
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * @return Route[] Route instances that point to this content
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param MenuNode $menu
     */
    public function addMenuNode(NodeInterface $menu)
    {
        $this->menuNodes->add($menu);
    }

    /**
     * @param MenuNode $menu
     */
    public function removeMenuNode(NodeInterface $menu)
    {
        $this->menuNodes->removeElement($menu);
    }

    /**
     * @return ArrayCollection of MenuNode that point to this content
     */
    public function getMenuNodes()
    {
        return $this->menuNodes;
    }

    /**
     * @param boolean $cachePrivate
     */
    public function setCachePrivate($cachePrivate)
    {
        $this->cachePrivate = $cachePrivate;
    }

    /**
     * @return boolean
     */
    public function getCachePrivate()
    {
        return $this->cachePrivate;
    }

    /**
     * @param int $cacheMaxAge
     */
    public function setCacheMaxAge($cacheMaxAge)
    {
        $this->cacheMaxAge = $cacheMaxAge;
    }

    /**
     * @return int
     */
    public function getCacheMaxAge()
    {
        return $this->cacheMaxAge;
    }

    /**
     * @param int $cacheSharedMaxAge
     */
    public function setCacheSharedMaxAge($cacheSharedMaxAge)
    {
        $this->cacheSharedMaxAge = $cacheSharedMaxAge;
    }

    /**
     * @return int
     */
    public function getCacheSharedMaxAge()
    {
        if ($this->getCachePrivate()) {
            //Share max age is only for public response
            return 0;
        }

        return $this->cacheSharedMaxAge;
    }

    /**
     * @param boolean $cacheMustRevalidate
     */
    public function setCacheMustRevalidate($cacheMustRevalidate)
    {
        $this->cacheMustRevalidate = $cacheMustRevalidate;
    }

    /**
     * @return boolean
     */
    public function getCacheMustRevalidate()
    {
        return $this->cacheMustRevalidate;
    }

    /**
     * To clear the front cache, we just need to update the LastCacheModifiedDate of the page
     * Front cache validation noticed that cache should be recomputed
     */
    public function clearCache()
    {
        $this->lastCacheModifiedDate = new \DateTime();
    }

    /**
     * @return string
     */
    public function getDescriptionTitle()
    {
        return $this->descriptionTitle;
    }

    /**
     * @param string $descriptionTitle
     */
    public function setDescriptionTitle($descriptionTitle)
    {
        $this->descriptionTitle = $descriptionTitle;
    }

    /**
     * @return string
     */
    public function getDescriptionContent()
    {
        return $this->descriptionContent;
    }

    /**
     * @param string $descriptionContent
     */
    public function setDescriptionContent($descriptionContent)
    {
        $this->descriptionContent = $descriptionContent;
    }

    /**
     * @return boolean
     */
    public function getDescriptionEnabled()
    {
        return $this->descriptionEnabled;
    }

    /**
     * @param boolean $descriptionEnabled
     */
    public function setDescriptionEnabled($descriptionEnabled)
    {
        $this->descriptionEnabled = $descriptionEnabled;
    }

    /**
     * @return int
     */
    public function getDescriptionMediaId()
    {
        return $this->descriptionMediaId;
    }

    /**
     * @param int $descriptionMediaId
     */
    public function setDescriptionMediaId($descriptionMediaId)
    {
        $this->descriptionMediaId = $descriptionMediaId;
    }

    /**
     * @return Media
     */
    public function getDescriptionMedia()
    {
        return $this->descriptionMedia;
    }

    /**
     * @param Media $descriptionMedia
     */
    public function setDescriptionMedia($descriptionMedia)
    {
        $this->descriptionMedia = $descriptionMedia;
        if ($descriptionMedia !== null) {
            $this->setDescriptionMediaId($descriptionMedia->getId());
        } else {
            $this->setDescriptionMediaId(null);
        }
    }

    /**
     * Check if the page has menu data, used when update the menus in EventListener
     *
     * @return bool
     */
    public function hasMenuData()
    {
        return isset($this->menuNodeId) && isset($this->menuNodeLabel);
    }
}
