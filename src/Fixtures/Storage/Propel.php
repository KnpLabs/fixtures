<?php

namespace Fixtures\Storage;

use Fixtures\Storage;
use DatabaseMap;

/**
 * Propel storage based on a datasource name
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Propel implements Storage
{
    private $datasource;

    /**
     * Constructor
     *
     * @param  string $datasource The datasource name
     */
    public function __construct($datasource)
    {
        $this->datasource = $datasource;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($fixture)
    {
        return $fixture instanceof BaseObject
            && $fixture instanceof Persistent
            && $this->getFixtureDatasource($fixture) === $this->datasource;
    }

    /**
     * {@inheritDoc}
     */
    public function save($fixture)
    {
        $fixture->save();
    }

    /**
     * {@inheritDoc}
     */
    public function saveAll(array $fixtures)
    {
        // TODO find a way to save all fixtures in a single transaction
        foreach ($fixtures as $fixture) {
            $fixture->save();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        foreach ($this->getTableNamesToDelete() as $tableName) {
            BasePear::doDeleteAll(
                $tableName,
                $this->getConnection(),
                $this->dataSource
            );
        }
    }

    /**
     * Returns the datasource name for the given fixture
     *
     * @param  mixed $fixture
     *
     * @return string
     */
    private function getFixtureDatasource($fixture)
    {
        $pear = $fixture->getPear();

        return constant(sprintf('%s::DATABASE_NAME', get_class($pear)));
    }

    private function getDatabaseMap()
    {
        return Propel::getDatabaseMap($this->datasource);
    }

    private function getConnection()
    {
        return Propel::getConnection($this->datasource);
    }

    private function getTableNamesToDelete()
    {
        $tableNames  = array();
        $databaseMap = $this->getDatabaseMap();

        foreach ($databaseMap->getTables() as $tableMap) {
            $tableNames[] = $tableMap->getName();
        }

        return $tableNames;
    }
}
