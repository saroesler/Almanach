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
    	$aid = FormUtil::getPassedValue('id',0,'GET');
    	
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
    	
    	$subscibeDates = $this->entityManager->getRepository('Almanach_Entity_SubscribeDate')->findBy(array('uid' => $uid));
    	foreach($subscibeDates as $subscibeDate){
    		$subscribedDates[$this->arrayHasDate($subscibeDate->getDid(), $myDates)] = 1;
    	}
    	
    	$adminDates = array();
    	
    	foreach($myDates as $key => $myDate){
    		$permission = ModUtil::apiFunc('Almanach', 'Date', 'getDatePermission', $myDate->getDid()); 
    		if(! $permission){
    			unset($myDates[$key]);
    			continue;
    		}
    		
    		if($permission == 2)
    			//get key by arrayHasDate
    			$adminDates[$this->arrayHasDate($myDate->getDid(), $myDates)] = 1;
    	}
    	
    	$oldKey = -1;
    	
    	$now = new DateTime();
    	
    	foreach($myDates as $myDate){
    		//set groupcolor if this date has no color:
    		if(strlen($myDate->getColor()) < 7 &&  $myDate->getGid() > 0){
    			$group = $this->entityManager->find('Almanach_Entity_Group', $myDate->getGid());
    			$myDate->setColor($group->getColor());
    		}
    		
    		if($myDate->getEnddate() < $now)
    			$oldKey ++;
    	}
    	
    	$isSubscribe = $this->entityManager->getRepository('Almanach_Entity_SubscribeAlmanach')->findBy(array('aid' => $aid, 'uid' => $uid));
    	
    	if(empty($isSubscribe))
    		$calendarSubscribtion = 0;
    	else
    		$calendarSubscribtion = 1;
    		
		$subalmanachs = ModUtil::apiFunc('Almanach', 'Heredity', 'getSubCalendar', $aid);
		$groupreturn = ModUtil::apiFunc('Almanach', 'Heredity', 'getGroupsOfDates', $myDates);
    	
    			
    	//get all keys, which dates are subscribed
    	$this->view
    		->assign('almanach', $almanach)
    		->assign('subalmanachs', $subalmanachs)
    		->assign('groups', $groupreturn['groups'])
    		->assign('noGroup', $groupreturn['noGroup'])
    		->assign('calendarSubscribtion', $calendarSubscribtion)
    		->assign('myDates', $myDates)
    		->assign('subscribedDates', $subscribedDates)
    		->assign('adminDates', $adminDates)
    		->assign('oldKey', $oldKey);
    		
    	if($almanach->getTemplate() != '')
			$tpl = file_exists(__DIR__."/../../../templates/".$almanach->getTemplate()) ? $almanach->getTemplate() : 'User/View.tpl';
		else
			$tpl = 'User/View.tpl';
    		 
    	return $this->view
    		->fetch($tpl);
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
    
    public function showDate(){
    	$did = FormUtil::getPassedValue('id',0,'GET');
    	
    	if($did <= 0)
    		return $this->__("No valid date!");
    		
    	$date = $this->entityManager->find('Almanach_Entity_Date', $did);
    	
    	if(empty($date))
    		return $this->__("No valid date!");
    	
    	$permission = ModUtil::apiFunc('Almanach', 'Date', 'getDatePermission', $did);
    	if(! $permission)
    		$this->throwForbiddenUnless(false);
    		
    	return $this->view
    		->assign('date', $date)
    		->assign('permission', $permission)
    		->fetch("User/ShowDate.tpl");
    }
}
