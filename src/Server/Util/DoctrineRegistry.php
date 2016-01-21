<?php

namespace ZfrOAuth2Module\Server\Util;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\ORMException;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class DoctrineRegistry
 */
class DoctrineRegistry extends AbstractManagerRegistry implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * {@inheritdoc}
     */
    protected function getService($name)
    {
        return $this->serviceManager->get($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function resetService($name)
    {
        $this->serviceManager->setService($name, null);
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasNamespace($alias)
    {
        foreach (array_keys($this->getManagers()) as $name) {
            try {
                return $this->getManager($name)->getConfiguration()->getEntityNamespace($alias);
            } catch (ORMException $e) {
            }
        }

        throw ORMException::unknownEntityNamespace($alias);
    }

    /**
     * {@inheritdoc}
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
