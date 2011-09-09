<?php

namespace Fixtures\Storage\Doctrine;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Storage based on a Doctrine MongoDB document manager
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class MongoDB extends Base
{
    /**
     * Constructor
     *
     * @param  DocumentManager $manager
     */
    public function __construct(DocumentManager $manager)
    {
        $this->setManager($manager);
    }

    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        $manager = $this->getManager();
        $classes = $manager->getMetadataFactory()->getAllMetadata();
        foreach ($classes as $class) {
            if ( ! $class->isMappedSuperclass) {
                $manager->getDocumentCollection($class->name)->drop();
            }
        }
    }
}
