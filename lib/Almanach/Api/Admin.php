<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Almanach_Api_Admin extends Zikula_AbstractApi
{
	/**
	 * @brief Get available admin panel links
	 *
	 * @return array array of admin links
	 */
	public function getlinks()
	{
		$links = array();
		/**********
		* Bereitet die Listen für die Ausgabe vor
		**********/
		
		
		if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_COMMENT)) {
			$links[] = array(
				'url'=> ModUtil::url('Almanach', 'admin', 'main'),
				'text'  => $this->__('Main'),
				'title' => $this->__('Main'),
				'class' => 'z-icon-es-home',
			);
		}
		
		if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN)) {
			$links[] = array(
				'url'=> ModUtil::url('Almanach', 'admin', 'groups'),
				'text'  => $this->__('Groups'),
				'title' => $this->__('show the groups'),
				'class' => 'z-icon-es-group',
			);
		}
		
		if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN)) {
			$links[] = array(
				'url'=> ModUtil::url('Almanach', 'admin', 'calendar'),
				'text'  => $this->__('Calendar'),
				'title' => $this->__('manage the calendars'),
				'class' => 'z-icon-es-cubes',
			);
		}
		
		if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_COMMENT)) {
            $links[] = array(
                'url' => ModUtil::url('Almanach', 'admin', 'editDate'),
                'text' => $this->__('Create new Date'),
                'title' => $this->__('Create new Date'),
                'class' => 'z-icon-es-add');
        }
        
        if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN)) {
			$links[] = array(
				'url'=> ModUtil::url('Almanach', 'admin', 'generalSettings'),
				'text'  => $this->__('General Settings'),
				'title' => $this->__('General Settings'),
				'class' => 'z-icon-es-config',
			);
		}
		
		return $links;
	}
	
	public function getAlmanachName($args)
	{
		$almanach = $this->entityManager->find('Almanach_Entity_Almanach', $args['aid']);
		return $almanach->getName();
	}
	
	public function getGroupName($args)
	{
		$group = $this->entityManager->find('Almanach_Entity_Group', $args['gid']);
		if(is_object($group))
			return $group->getName();
		else
			return '';
	}
	
	public function getGroupColor($args)
	{
		if($args['gid'] > 0){
			$group = $this->entityManager->find('Almanach_Entity_Group', $args['gid']);
			return $group->getColor();
		} else {
			return '#000000';
		}
	}
	
	/*
	* This function tests, if there is a overlapping of this date to an other one
	* which entered in one calendar witch herits. It tested the given calendar, too.
	* @return 	0, if there is a forbidden overlapping
	*			1, if there is a allowed overlapping
	*			2, if there is no overlapping
	*/
	public function getOverlappingState($args)
	{
		$group = $this->entityManager->find('Almanach_Entity_Group', $args['gid']);
		return $group->getName();
	}
	
	public function getContactPerson($args){
		$vars = UserUtil::getVars($args['uid']);
        if($vars['__ATTRIBUTES__'][$this->getVar('FormOfAddressField')] == 2)
        	$sex = $this->__('Mr.');
        else{
        	$sex = $this->__('Mrs.');
        }
        return $sex . ' ' .  substr ( $vars['__ATTRIBUTES__'][$this->getVar('FirstNameField')] , 0, 1 ) . '. ' . $vars['__ATTRIBUTES__'][$this->getVar('SurnameField')];
	}
	
	public function getUserList(){
		$users = UserUtil::getUsers();
		
		$userlist = array();
		
		foreach($users as $user){
			$userlist[] = array(
				'text' => $user['uname'],
				'value' => $user['uid']
			);
		}
		
		return $userlist;
	}
}
