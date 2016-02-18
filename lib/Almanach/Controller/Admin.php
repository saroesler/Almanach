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
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('Almanach::', '::', ACCESS_MODERATE));
    	
    	/**
    	* This creates the mainpage, witch shows:
    	* - personal dates
    	* - subscibted dates and calendars
    	* - visible calendars
    	**/
    	return $this->view
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
}

