<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 14.10.18
 * Time: 17:13
 * To change this template use File | Settings | File Templates.
 * Generatour rutingu na podstawie tabli Section
 */

namespace AppBundle\Service;
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractServiceConfigurator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Section;

class RouteService extends AbstractServiceConfigurator{

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return Section|array
     */

    public function getList()
    {
        return $this->em->getRepository(Section::class)
                    ->getSectionRoute();
    }
}