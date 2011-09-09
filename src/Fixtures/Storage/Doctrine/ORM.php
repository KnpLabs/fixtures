<?php

namespace Fixtures\Storage\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Internal\CommitOrderCalculator;

/**
 * Storage based on a Doctrine ORM entity manager
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class ORM extends Base
{
    /**
     * Constructor
     *
     * @param  EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->setManager($manager);
    }

    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        $classes = $this->getEntityClasses();

        foreach ($this->getTruncateOrderTables($classes) as $table) {
            $this->truncateTable($table);
        }
    }

    /**
     * Returns the metadata of all the entity classes
     *
     * @return array An array of ClassMetadata instances
     */
    private function getEntityClasses()
    {
        return array_filter(
            $this->getAllMetadata(),
            function ($metadata) {
                return false === $metadata->isMappedSuperclass;
            }
        );
    }

    /**
     * Returns the table names ordered to be truncated for the given classes
     *
     * @param  array $classes An array of ClassMetadata instances
     *
     * @return array An array of table names
     */
    private function getTruncateOrderTables(array $classes)
    {
        $tables = $this->getAssociationTables($classes);

        foreach ($this->getReverseCommitOrder($classes) as $class) {
            $tables[] = $class->table['name'];
        }

        return $tables;
    }

    /**
     * Returns the association table names for all the given classes
     *
     * @param  array $classes An array of ClassMetadata instances
     *
     * @return array An array of table names
     */
    private function getAssociationTables($classes)
    {
        $tables = array();
        foreach ($classes as $class) {
            foreach ($class->associationMappings as $association) {
                if ($association['isOwningSide'] && $association['type'] === ClassMetadata::MANY_TO_MANY) {
                    $tables[] = $association['joinTable']['name'];
                }
            }
        }

        return $tables;
    }

    /**
     * Returns the reverse commit order for the given classes
     *
     * @param  array $classes An array of ClassMetadata instances
     *
     * @return array
     */
    private function getReverseCommitOrder(array $classes)
    {
        $calculator = new CommitOrderCalculator();
        foreach ($classes as $class) {
            $calculator->addClass($class);
        }

        return array_reverse($calculator->getCommitOrder());
    }

    /**
     * Truncates the specified table
     *
     * @param  string $table The name of the table to truncate
     */
    private function truncateTable($table)
    {
        $connection  = $this->getManager()->getConnection();
        $platform    = $connection->getDatabasePlatform();

        if ('mysql' === $platform->getName()) {
            // TODO get rid of this hack
            $connection->executeUpdate('DELETE FROM `' . $table . '`');
            $connection->exec('ALTER TABLE `' . $table . '` AUTO_INCREMENT=0');
        } else {
            $truncateSql = $platform->getTruncateTableSQL($table, true);
            $connection->executeUpdate($truncateSql);
        }
    }
}
