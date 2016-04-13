<?php

/**
 * This is the admin controller class providing navigation and interaction functionality.
 */
class Almanach_Controller_Admin extends Zikula_AbstractController
{	
    /**
     * @brief Main function.
     * @throws Zikula_Forbidden If not ACCESS_ADMIN
     * @return template Admin/Main.tpl
     * 
     * @author Sascha Rösler
     */
    public function main()
    {
    	$uid = SessionUtil::getVar('uid');
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_COMMENT));
    	
    	
    	/*
    	* Delete old Dates
    	*/
    	$cutdate = new \Datetime();
    	$cutdate->sub(new DateInterval('P' . ($this->getVar('Savetime')) . 'D'));
    	
    	$em = $this->getService('doctrine.entitymanager');
		$qb = $em->createQueryBuilder();
		$qb->select('p')
		   ->from('Almanach_Entity_Date', 'p')
		   ->where('p.enddate <= :date')
		   ->setParameter('date', $cutdate->format('Y-m-d H:i'));
		$dates = $qb->getQuery()->getArrayResult();
		
		foreach($dates as $tmpdate){
			$did = $tmpdate['did'];
			$thisalmanachs = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('did' => $did));
			//delete this date from all almanachs:
			foreach($thisalmanachs as $thisalmanach){
				$this->entityManager->remove($thisalmanach);
				$this->entityManager->flush();
			}
		
			//delete this date from all subscribtions:
			$subscribtions = $this->entityManager->getRepository('Almanach_Entity_SubscribeDate')->findBy(array('did' => $did));
			foreach($subscribtions as $subscribtion){
				$this->entityManager->remove($subscribtion);
				$this->entityManager->flush();
			}
			$date = $this->entityManager->find('Almanach_Entity_Date', $did);
			$this->entityManager->remove($date);
			$this->entityManager->flush();
		}
    	
    	/**
    	* This creates the mainpage, witch shows:
    	* - personal dates
    	* - subscibted dates and calendars
    	* - visible calendars
    	**/
    	
    	
    	//get visible calendars
    	$almanachs = $this->entityManager->getRepository('Almanach_Entity_Almanach')->findBy(array());
    	$calendarSubscribtions = array();
    	foreach($almanachs as $key => $almanach){
    		if(!(SecurityUtil::checkPermission('Almanach::Almanach', '::'. $almanach->getAid() , ACCESS_READ))) {
	        	unset($almanachs[$key]);
	        } else {
	        	$mySubscribtion = $this->entityManager->getRepository('Almanach_Entity_SubscribeAlmanach')->findBy(array('aid' => $almanach->getAid(), 'uid' => $uid));
	        	
	        	if(! empty($mySubscribtion)){
	        		$calendarSubscribtions[$key] = 1;
	        	}
	        }
    	}
    	
    	//get my dates and subscribed dates:
    	$subscibeDates = $this->entityManager->getRepository('Almanach_Entity_SubscribeDate')->findBy(array('uid' => $uid));
    	$subscibeAlmanachs = $this->entityManager->getRepository('Almanach_Entity_SubscribeAlmanach')->findBy(array('uid' => $uid));
    	$myDates = $this->entityManager->getRepository('Almanach_Entity_Date')->findBy(array('uid' => $uid));

    	//merge myDates and subscibeDates
    	$myDates = $this->mergeDates($subscibeDates, $myDates);
    	
    	//merge myDates and dates of subscribed almanach
    	foreach($subscibeAlmanachs as $subscibeAlmanach){
    		$almanachDates = ModUtil::apiFunc('Almanach', 'Heredity', 'getAllDates', array('aid' => $subscibeAlmanach->getAid()));
    		
    		$myDates = $this->mergeDates($almanachDates, $myDates);
    	}
    	
    	usort($myDates, array("Almanach_Controller_Admin", "dateCmp"));
    	
    	$subscribedDates = array();
    	
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
    	
    	//get all keys, which dates are subscribed
    	return $this->view
    		->assign('almanachs', $almanachs)
    		->assign('calendarSubscribtions', $calendarSubscribtions)
    		->assign('myDates', $myDates)
    		->assign('subscribedDates', $subscribedDates)
    		->assign('adminDates', $adminDates)
    		->assign('oldKey', $oldKey)
    		->fetch('Admin/Main.tpl');
    }
    
    /*
    * This function merges to date arrays. Dates with the same did are only one time in the
    * merged array.
    */
    protected function mergeDates($newDates, $myDates){
    	foreach($newDates as $newDate){
			$did = $newDate->getDid();
			if($this->arrayHasDate($did , $myDates) > -1){
				continue;
			}
			
			//if date is not in mydate: add it
			$myDates[] = $newDate;
		}	
		return $myDates;
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
		return -1;
    }

	public function dateCmp($a, $b)
	{
		if($a->getStartdate() == $b->getStartdate())
			return ($a->getEnddate() < $b->getEnddate()) ? -1: 1;
		return ($a->getStartdate() < $b->getStartdate()) ? -1: 1;
	}
	
    /**
     * @brief Group view function.
     * @throws Zikula_Forbidden If not ACCESS_ADMIN
     * @return template Admin/Groups.tpl
     */
    public function groups()
    {
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN));
    	
    	/**
    	* This creates the group page, to create, edit and delete groups
    	**/
    	$groups = $this->entityManager->getRepository('Almanach_Entity_Group')->findBy(array());
    	return $this->view
    		->assign('groups', $groups)
    		->fetch('Admin/Groups.tpl');
    }
    
    /**
     * @brief Calender administrate function.
     * @throws Zikula_Forbidden If not ACCESS_ADMIN
     * @return template Admin/Calendar.tpl
     */
    public function calendar()
    {
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN));
    	
    	/**
    	* This creates the group page, to create, edit and delete groups
    	**/
    	$calendars = $this->entityManager->getRepository('Almanach_Entity_Almanach')->findBy(array());
    	return $this->view
    		->assign('calendars', $calendars)
    		->fetch('Admin/Calendar.tpl');
    }
    
    /**
     * @brief Calender administrate function.
     * @throws Zikula_Forbidden If not ACCESS_ADMIN
     * @return template Admin/Calendar.tpl
     */
    public function editCalendar()
    {
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN));
    	
		$aid = FormUtil::getPassedValue('id',null,'GET');
		
		$form = FormUtil::newForm('Almanach', $this);
		return $form->execute('Admin/CalendarEdit.tpl', new Almanach_Handler_AlmanachEdit());
    }
    
    /**
     * @brief Date administrate function.
     * @throws Zikula_Forbidden If not ACCESS_COMMENT
     * @return template Admin/Calendar.tpl
     */
    public function editDate()
    {
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_COMMENT));
    	
		$did = FormUtil::getPassedValue('id',null,'GET');
		
		$form = FormUtil::newForm('Almanach', $this);
		return $form->execute('Admin/DateEdit.tpl', new Almanach_Handler_DateEdit());
    }
    
    /**
     * @brief general settings administrate function.
     * @throws Zikula_Forbidden If not ACCESS_ADMIN
     * @return template Admin/GeneralSettings.tpl
     */
    public function generalSettings()
    {
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN));
		
		$form = FormUtil::newForm('Almanach', $this);
		return $form->execute('Admin/GeneralSettings.tpl', new Almanach_Handler_GeneralSettings());
    }
    
    public function googleTest()
    {
    	echo "u";
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_ADMIN));
    	echo "u";
		$googleApi = ModUtil::apiFunc('Almanach', 'GoogleCalendarApi', 'getApi');
		echo "u";
		$almanachs = $this->entityManager->getRepository('Almanach_Entity_Almanach')->findBy(array());
		echo "u";
		foreach($almanachs as $almanach){
			if($almanach->getGoogleCalendarId() != ''){
				echo "<br/>Für Kalender ".$almanach->getName()."(".$almanach->getGoogleCalendarId().")";
				try{
					print_r($googleApi->getEvents($almanach->getGoogleCalendarId(), 500, $this->getVar('Savetime')));
				} catch(Exception $e){
					$e->getMessage();
				}
			}
		}
    }
}

