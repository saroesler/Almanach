<?php
/**
 * Copyright Zikula Foundation 2010 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license MIT
 * @package ZikulaExamples_ExampleDoctrine
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Form handler for create and edit.
 */
class Almanach_Handler_GeneralSettings extends Zikula_Form_AbstractHandler
{
    /**
     * Setup form.
     *
     * @param Zikula_Form_View $view Current Zikula_Form_View instance.
     *
     * @return boolean
     */
    public function initialize(Zikula_Form_View $view)
    {        
    	$googleApiExist = ModUtil::apiFunc('Almanach', 'GoogleCalendarApi', 'apiExist');
    	if($googleApiExist == 1)
    		LogUtil::RegisterStatus($this->__("Google Calendar Api is not installed!"));
		$view->assign('savetime',$this->getVar('Savetime'));
        $view->assign('datecolor',$this->getVar('AllowDateColoring'));
        $view->assign('formofaddress',$this->getVar('FormOfAddressField'));
        $view->assign('surname',$this->getVar('SurnameField'));
        $view->assign('firstname',$this->getVar('FirstNameField'));
        $view->assign('googleApiAddress',$this->getVar('googleApiAddress'));
        $view->assign('googleApiExist',$googleApiExist);

        // assign current values to form fields
        return true;
    }

    /**
     * Handle form submission.
     *
     * @param Zikula_Form_View $view  Current Zikula_Form_View instance.
     * @param array            &$args Args.
     *
     * @return boolean
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
    	$aid = FormUtil::getPassedValue('id',null,'GET');
        $url = ModUtil::url('Almanach', 'admin', 'main' );
        if ($args['commandName'] == 'cancel') {
            return $view->redirect($url);
        }


        // check for valid form
        if (!$view->isValid()) {
            return false;
        }
        // load form values
        $d = $view->getValues();
        print_r($d);
        
        $this->setVar('Savetime', $d['savetime']);
        $this->setVar('AllowDateColoring', $d['datecolor']);
        $this->setVar('FormOfAddressField', $d['formofaddress']);
        $this->setVar('SurnameField', $d['surname']);
        $this->setVar('FirstNameField', $d['firstname']);
        $this->setVar('googleApiAddress', $d['googleApiAddress']);

        return $view->redirect($url);
    }
}
