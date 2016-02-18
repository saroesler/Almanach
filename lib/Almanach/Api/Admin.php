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
				'class' => 'z-icon-es-config',
			);
		}
		
		if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN)) {
			$links[] = array(
				'url'=> ModUtil::url('Almanach', 'admin', 'groups'),
				'text'  => $this->__('Groups'),
				'title' => $this->__('show the groups'),
				'class' => 'z-icon-es-display',
				'links' => $outputlist
			);
		}
		
		if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN)) {
			$links[] = array(
				'url'=> ModUtil::url('Almanach', 'admin', 'calendar'),
				'text'  => $this->__('Calendar'),
				'title' => $this->__('manage the calendars'),
				'class' => 'z-icon-es-display',
				'links' => $outputlist
			);
		}
		
		if (SecurityUtil::checkPermission('Almanach::', '::', ACCESS_COMMENT)) {
            $links[] = array(
                'url' => ModUtil::url('Almanach', 'admin', 'editDate'),
                'text' => $this->__('Create new Date'),
                'class' => 'z-icon-es-cubes');
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
		return $group->getName();
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
}
