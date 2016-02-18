<?php
/**
 * Installer.
 */
class Almanach_Installer extends Zikula_AbstractInstaller
{

	/**
	 * @brief Provides an array containing default values for module variables (settings).
	 * @return array An array indexed by variable name containing the default values for those variables.
	 *
	 * @author Sascha Rösler
	 */
	protected function getDefaultModVars()
	{
		return array();
	}

	/**
	 * Install the Almanach module.
	 *
	 * This function is only ever called once during the lifetime of a particular
	 * module instance.
	 *
	 * @return boolean True on success, false otherwise.
	 */
	public function install()
	{
		$this->setVars($this->getDefaultModVars());

		// Create database tables.
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_Almanach'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_AlmanachElement'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_Color'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_Data'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_Group'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_Heredity'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_SubscribeAlmanach'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		try {
			DoctrineHelper::createSchema($this->entityManager, array(
				'Almanach_Entity_SubscribeDate'
			));
		} catch (Exception $e) {
			return LogUtil::registerError($e);
		}
		
		HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
		
		return true;
	}


	/**
	 * Upgrade the Almanach module from an old version
	 *
	 * This function must consider all the released versions of the module!
	 * If the upgrade fails at some point, it returns the last upgraded version.
	 *
	 * @param  string $oldVersion   version number string to upgrade from
	 *
	 * @return mixed  true on success, last valid version string or false if fails
	 */
	public function upgrade($oldversion)
	{
		switch($oldversion)
		{
			case "0.0.1":
				try {
					DoctrineHelper::updateSchema($this->entityManager, array('Almanach_Entity_Color'));
					DoctrineHelper::updateSchema($this->entityManager, array('Almanach_Entity_Group'));
				} catch (Exception $e) {
					return LogUtil::registerError($e);
				}
			case "0.0.2":
				try {
					DoctrineHelper::updateSchema($this->entityManager, array('Almanach_Entity_Date'));
				} catch (Exception $e) {
					return LogUtil::registerError($e);
				}
				break;
		}
		return true;
	}


	/**
	 * Uninstall the module.
	 *
	 * This function is only ever called once during the lifetime of a particular
	 * module instance.
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function uninstall()
	{
		// Drop database tables
		DoctrineHelper::dropSchema($this->entityManager, array(
			'Almanach_Entity_Data'
		));
		
		DoctrineHelper::dropSchema($this->entityManager, array(
			'Almanach_Entity_General'
		));
		
		$this->delVars();
		
		HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());

		return true;
	}

}
