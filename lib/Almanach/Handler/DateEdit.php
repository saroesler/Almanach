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
class Almanach_Handler_DateEdit extends Zikula_Form_AbstractHandler
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
        // Get the id.
        $did = FormUtil::getPassedValue('id',null,'GET');
        if ($did) {
            $date = $this->entityManager->find('Almanach_Entity_Date', $did);

            if (!$date) {
                return LogUtil::registerError($this->__f('Date with id %s not found', $did));
            }
            
            if(!SecurityUtil::checkPermission('Almanach::Group', '::'. $date->getGid() , ACCESS_EDIT)) {
            	LogUtil::RegisterError($this->__("You cant edit this date."));
            	return false;
            }
            
			$connections = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('did'=>$did));
			
			$almanachSelector = $this->entityManager->getRepository('Almanach_Entity_Almanach')->findBy(array('input'=>1));
			$almanachHide = array();
			foreach($almanachSelector as $key => $item){
				if (!SecurityUtil::checkPermission('Almanach::Almanach', '::'. $item->getAid() , ACCESS_EDIT)) {
					unset($almanachSelector[$key]);
					continue;
				}
				$hasalmanach = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('did'=>$did, 'aid'=>$item->getAid()));
				if(! empty($hasalmanach)){
					$almanachHide[$key] = true;
				} else {
					$almanachHide[$key] = false;
				}
			}			
        } else {
            $date = new Almanach_Entity_Date(); 
            $connections = array();
            
            $almanachSelector = $this->entityManager->getRepository('Almanach_Entity_Almanach')->findBy(array('input'=>1));
			$almanachHide = array();
			foreach($almanachSelector as $key => $item){
				if (!SecurityUtil::checkPermission('Almanach::Almanach', '::'. $item->getAid() , ACCESS_EDIT)) {
					unset($almanachSelector[$key]);
					continue;
				}
			}
        }

		$groupSelection = array();
		$groupSelection[] = array(
			'text' => $this->__('No Group'),
			'value' => 0,
		);
		$groups = $this->entityManager->getRepository('Almanach_Entity_Group')->findBy(array());
		foreach($groups as $key => $item){
			if (SecurityUtil::checkPermission('Almanach::Group', '::'. $item->getGid() , ACCESS_EDIT)) {
				$groupSelection[] = array(
					'text' => $item->getName(),
					'value' => $item->getGid(),
				);
			}
		}
		
		//visibility of this date
		$visibilitySelection = array();
		$visibilitySelection[] = array(
			'text' => $this->__('public: This Date can be seen by everyone.'),
			'value' => 0,
		);
		$visibilitySelection[] = array(
			'text' => $this->__('hidden: This Date can be seen by those, how has a higher permission.'),
			'value' => 1,
		);
		$visibilitySelection[] = array(
			'text' => $this->__('secret: This Date can be seen by those, how can create Dates in the Calendar.'),
			'value' => 2,
		);
		
		$view->assign('date',$date);
        $view->assign('connections',$connections);
        $view->assign('almanachSelector',$almanachSelector);
        $view->assign('almanachHide',$almanachHide);
        $view->assign('groupSelection',$groupSelection);
        $view->assign('visibilitySelection',$visibilitySelection);

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
    	$did = FormUtil::getPassedValue('id',null,'GET');
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
        
        if(strtotime($d['startdate']) > strtotime($d['enddate'])){
        	LogUtil::RegisterError($this->__("The end can not be before the beginning!"));
        	return false;
        }
        
        if (!SecurityUtil::checkPermission('Almanach::Group', '::'. $d['gid'] , ACCESS_EDIT)) {
        	LogUtil::RegisterError($this->__("You dont have the permission to create a date for the selected group. Please choose one you can create a date for."));
        	return false;
        }
        
        if($did > 0){
        	$date = $this->entityManager->find('Almanach_Entity_Date', $did);
        }
        else{
        	$date = new Almanach_Entity_Date();
        	$date->setUid(SessionUtil::getVar('uid'));
        	$date->setCreationdate('');
        }
        	
        // merge user and save everything
        $date->merge($d);
        $this->entityManager->persist($date);
        $this->entityManager->flush();
        print_r($d);
        die();
        
        $did = $date->getDid();
        
        //work connections
        $connections = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('did'=>$did));
        foreach($connections as $key => $item){
        	if (!SecurityUtil::checkPermission('Almanach::Almanach', '::'. $item->getAid() , ACCESS_EDIT)) {
        		LogUtil::RegisterError($this->__("You dont have the permission to edit the connection to the calendar %s." , array($item->getAlmanachName())));
        		continue;
        	}
        	
        	$myColor = FormUtil::getPassedValue('CalendarColorinput' . $item->getEid() ,null,'POST');
        	$deleted = FormUtil::getPassedValue('connectionDeleted' . $item->getEid() ,null,'POST');
        	if($deleted){
        		$this->entityManager->remove($item);
				$this->entityManager->flush();
				LogUtil::RegisterStatus($this->__("Date sucessfully deleted of calendar %s." , array($item->getAlmanachName())));
        	} elseif($myColor != $item->getColor()){
		    	$item->setColor($myColor);
		    	$this->entityManager->persist($item);
				$this->entityManager->flush();
				LogUtil::RegisterStatus($this->__("Date sucessfully edited in calendar %s.", array($item->getAlmanachName())));
			}
        }
        
        //create new color
        $newCalendarsNum = FormUtil::getPassedValue('newCalendarsNum',null,'POST');
        
        for($i=0; $i < $newCalendarsNum; $i ++){
        	$aid = FormUtil::getPassedValue('newCalendarAid' . $i ,null,'POST');
        	if($aid == 0)
        		continue;
        		
    		if (!SecurityUtil::checkPermission('Almanach::Almanach', '::'. $aid , ACCESS_EDIT)) {
        		LogUtil::RegisterError($this->__f("You dont have the permission to input the date into the calendar %s.", array($almanachName)));
        		continue;
        	}
        	
        	$overlapping = ModUtil::apiFunc('Almanach', 'Overlapping', 'getOverlappingState', array('aid' => $aid, 'did' => $did));
        	$almanachName = ModUtil::apiFunc('Almanach', 'Admin', 'getAlmanachName', array('aid' => $aid));
        	if($overlapping['state'] < 2){
        		$overlapAlmanachName = ModUtil::apiFunc('Almanach', 'Admin', 'getAlmanachName', array('aid' => $overlapping['aid']));
        		$overlapDate = $this->entityManager->find('Almanach_Entity_Date', $overlapping['did']);
        	}
        	
        	if($overlapping['state'] == 0){
        		LogUtil::RegisterError($this->__f("This date overlaps with an other date in calendar %s. So the date cant be entered into calendar %s. Please contact %s.", array($olverlapAlmanachName, $almanachName, $overlapDate->getUserName())));
        		continue;
        	}
        	if($overlapping['state'] == 1){
        		LogUtil::RegisterWarning($this->__f("Please notice that this date overlaps with an other date in calendar %s. Please contact %s.", array($overlapAlmanachName, $overlapDate->getUserName())));
        		continue;
        	}
        	
    		$myColor = FormUtil::getPassedValue('CalendarColorinput' . $i ,null,'POST');
        	$connection = new Almanach_Entity_AlmanachElement();
			$connection->setDid($did);
			$connection->setAid($aid);
			$connection->setColor($myColor);
			$this->entityManager->persist($connection);
			$this->entityManager->flush();
			LogUtil::RegisterStatus($this->__f("The date has been entered into calendar %s successfully.", array($almanachName)));
        }

        return $view->redirect($url);
    }
}
