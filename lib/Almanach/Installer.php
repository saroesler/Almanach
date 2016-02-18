<?php
/**
 * Installer.
 */
class Almanach_Installer extends Zikula_AbstractInstaller
{
	/*
	*	PROFILE_STATE
	* This creates fields in Profile for the Surname, the first name
	* and the form of address. This fields are important!
	*/
	const PROFILE_STATE = 'DE';
	
	/**
	 * @brief Provides an array containing default values for module variables (settings).
	 * @return array An array indexed by variable name containing the default values for those variables.
	 *
	 * @author Sascha Rösler
	 */
	protected function getDefaultModVars()
	{
		if(self::PROFILE_STATE == 'DE' || PROFILE_STATE == 'EN')
			return array(
			'Savetime' => 1095,
			'AllowDateColoring' => 1,
			'FormOfAddressField' => 'sex',
			'SurnameField' => 'surname',
			'FirstNameField' => 'first_name'
			);
		else
			return array(
			'Savetime' => 1095,
			'AllowDateColoring' => 1,
			'FormOfAddressField' => '',
			'SurnameField' => '',
			'FirstNameField' => ''
			);
	}
	
	public function addProfileProperties(){
		if(self::PROFILE_STATE == 'DE'){
			ModUtil::apiFunc('Profile', 'admin', 'create', array(
				'label'          => "Anrede",
				'attribute_name' => sex,
				'required'       => 1,
				'viewby'         => 2,
				'dtype'          => 3,
				'displaytype'    => 3,
				'listoptions'    => "@@Frau@1@@Herr@2",
				'note'           => "",
			));
			ModUtil::apiFunc('Profile', 'admin', 'create', array(
				'label'          => "Vorname",
				'attribute_name' => first_name,
				'required'       => 1,
				'viewby'         => 2,
				'dtype'          => 0,
				'displaytype'    => 0,
				'listoptions'    => "",
				'note'           => "Ihr Vorname",
			));
			ModUtil::apiFunc('Profile', 'admin', 'create', array(
				'label'          => "Vorname",
				'attribute_name' => surname,
				'required'       => 1,
				'viewby'         => 2,
				'dtype'          => 0,
				'displaytype'    => 0,
				'listoptions'    => "",
				'note'           => "Ihr Nachname",
			));
		}
		
		
		if(self::PROFILE_STATE == 'EN'){
			ModUtil::apiFunc('Profile', 'admin', 'create', array(
				'label'          => "Form of address",
				'attribute_name' => sex,
				'required'       => 1,
				'viewby'         => 2,
				'dtype'          => 3,
				'displaytype'    => 3,
				'listoptions'    => "@@Missus@1@@Mister@2",
				'note'           => "",
			));
			ModUtil::apiFunc('Profile', 'admin', 'create', array(
				'label'          => "first name",
				'attribute_name' => first_name,
				'required'       => 1,
				'viewby'         => 2,
				'dtype'          => 0,
				'displaytype'    => 0,
				'listoptions'    => "",
				'note'           => "your first name",
			));
			ModUtil::apiFunc('Profile', 'admin', 'create', array(
				'label'          => "Surname",
				'attribute_name' => surname,
				'required'       => 1,
				'viewby'         => 2,
				'dtype'          => 0,
				'displaytype'    => 0,
				'listoptions'    => "",
				'note'           => "your surname",
			));
		}
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
		$this->addProfileProperties();

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
			case "0.0.3":
				$this->setVars($this->getDefaultModVars());
				$this->addProfileProperties();
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
