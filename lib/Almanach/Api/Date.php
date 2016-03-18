<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Almanach_Api_Date extends Zikula_AbstractApi
{	

	/*
	* get admin, if date has uid, or user is member of the group
	* or user is calendar administrator
	*@return: 0: no permission, 1: read permission, 2: admin permission
	*/
	public function getDatePermission($did)
	{
		
		$date = $this->entityManager->find('Almanach_Entity_Date', $did);
		
		$uid = SessionUtil::getVar('uid');
		
		$hasRead = 0;
		
		if($date->getUid() == $uid)
			return 2;	//has admin
		if($date->getGid() > 0){
			if(SecurityUtil::checkPermission('Almanach::Group', '::'. $date->getGid() , ACCESS_EDIT))
				return 2;	//has admin
		}
		
		//get all almanachs of this date:
		$thisalmanachs = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('did' => $did));
		
		foreach($thisalmanachs as $thisalmanach){
			//add because of the almanach access
			if(SecurityUtil::checkPermission('Almanach::Almanach', '::'. $thisalmanach->getAid() , ACCESS_ADD)){
				return 2;	//has admin
			}	
			
			if(SecurityUtil::checkPermission('Almanach::Almanach', '::'. $thisalmanach->getAid() , ACCESS_EDIT)){
				$hasRead = 1;	//read all
			}	
			
			if(SecurityUtil::checkPermission('Almanach::Almanach', '::'. $thisalmanach->getAid() , ACCESS_MODERATE) && $date->getVisibility() < 2){
				$hasRead = 1;	//dont read secret
			}
			
			if(SecurityUtil::checkPermission('Almanach::Almanach', '::'. $thisalmanach->getAid() , ACCESS_READ) && $date->getVisibility() < 1){
				$hasRead = 1; //read only publics
			}
		}
		
		return $hasRead;
	}
}
