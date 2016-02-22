<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Almanach_Controller_User extends Zikula_AbstractController
{
    /**
     * @brief Main function.
     * @return string
     * 
     * @author Sascha Rösler
     */
    public function main()
    {
        return true;
    }
    
    public function view()
    {
    	$aid = FormUtil::getPassedValue('aid',0,'GET');
    	
    	if($aid <= 0)
    		return $this->__("No valid calendar!");
    		
    	$almanach = $this->entityManager->find('Almanach_Entity_Almanach', $aid);
    	
    	if(empty($almanach))
    		return $this->__("No valid calendar!");
    	
    	$uid = SessionUtil::getVar('uid');
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::Almanach', '::' . $aid, ACCESS_READ));
    	
    	$myDates = ModUtil::apiFunc('Almanach', 'Heredity', 'getAllDates', array('aid' => $aid));
    	
    	usort($myDates, array("Almanach_Controller_Admin", "dateCmp"));
    	
    	$subscribedDates = array();
    	
    	foreach($subscibeDates as $subscibeDate){
    		$subscribedDates[$this->arrayHasDate($subscibeDate->getDid(), $myDates)] = 1;
    	}
    	
    	
    	foreach($myDates as $key => $myDate){    		
    		//set admin
    		if($myDate->getUid() == $uid 
    		|| SecurityUtil::checkPermission('Almanach::Group', '::'. $myDate->getGid() , ACCESS_EDIT)
    		|| $myDate->getVisibility() == 0) {
    			continue;
    		}
    		
    		//get all almanachs of this date:
    		$thisalmanachs = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('did' => $myDate->getDid()));
    		
    		$tmp = 0;
    		foreach($thisalmanachs as $thisalmanach){
    			if(SecurityUtil::checkPermission('Almanach::Almanach', '::'. $thisalmanach->getAid() , ACCESS_MODERATE) && $myDate->getVisibility() == 1){
    				$tmp = 1;
    				break;
				}		
    		}
    		
    		if($tmp)
    			continue;
    		
    		unset($myDates[$key]);
    	}
    		
    	$adminDates = array();
    	$oldKey = -1;
    	
    	$now = new DateTime();
    	/*
    	* can edit this date, if date has uid, or user is member of the group
    	* or user is calendar administrator
    	*/
    	foreach($myDates as $myDate){
    		//set groupcolor if this date has no color:
    		if(strlen($myDate->getColor()) < 7 &&  $myDate->getGid() > 0){
    			$group = $this->entityManager->find('Almanach_Entity_Group', $myDate->getGid());
    			$myDate->setColor($group->getColor());
    		}
    		
    		if($myDate->getEnddate() < $now)
    			$oldKey ++;
    		
    		//set admin
    		if($myDate->getUid() == $uid ||
    		SecurityUtil::checkPermission('Almanach::Group', '::'. $myDate->getGid() , ACCESS_EDIT)) {
    			$adminDates[$this->arrayHasDate($myDate->getDid(), $myDates)] = 1;
    			continue;
    		}
    		
    		//get all almanachs of this date:
    		$thisalmanachs = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('did' => $myDate->getDid()));
    		
    		foreach($thisalmanachs as $thisalmanach){
    			if(SecurityUtil::checkPermission('Almanach::Almanach', '::'. $thisalmanach->getAid() , ACCESS_ADD)){
    				$adminDates[$this->arrayHasDate($myDate->getDid(), $myDates)] = 1;
    				break;
				}		
    		}
    	}
    	
    	$isSubscribe = $this->entityManager->getRepository('Almanach_Entity_SubscribeAlmanach')->findBy(array('aid' => $aid, 'uid' => $uid));
    	
    	if(empty($isSubscribe))
    		$calendarSubscribtion = 0;
    	else
    		$calendarSubscribtion = 1;
    	
    	//get all keys, which dates are subscribed
    	return $this->view
    		->assign('almanach', $almanach)
    		->assign('calendarSubscribtion', $calendarSubscribtion)
    		->assign('myDates', $myDates)
    		->assign('subscribedDates', $subscribedDates)
    		->assign('adminDates', $adminDates)
    		->assign('oldKey', $oldKey)
    		->fetch('Admin/Main.tpl');
    }
    
    /*
    * This function searches an array, if there is a date
    *  with a given did.
    */
    protected function arrayHasDate($did, $myDates){
    	foreach($myDates as $key => $myDate){
    		if($myDate->getDid() == $did)
    			return $key;
		}
		return false;
    }
}
