<?php
require_once('./Services/Database/interfaces/interface.ilAtomQuery.php');

/**
 * Class ilAtomQueryLock
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 *         Implements Atom-Queries with Table Locks, currently used in all other implementations than Galera
 */
class ilAtomQueryLock extends ilAtomQueryBase implements ilAtomQuery {

	/**
	 * Fire your Queries
	 *
	 * @throws \ilAtomQueryException
	 */
	public function run() {
		$this->checkBeforeRun();
		$this->runWithLocks();
	}


	/**
	 * @throws \ilAtomQueryException
	 */
	protected function runWithLocks() {
		$this->ilDBInstance->lockTables($this->getLocksForDBInstance());
		$this->runQueries();
		$this->ilDBInstance->unlockTables();
	}
}
