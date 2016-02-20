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
    	
    	return $this->view
    		->assign('almanachs', $almanachs)
    		->assign('calendarSubscribtions', $calendarSubscribtions)
    		->fetch('Admin/Main.tpl');
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
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_MODERATE));
    	
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
}

