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
class Almanach_Handler_AlmanachEdit extends Zikula_Form_AbstractHandler
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
        $aid = FormUtil::getPassedValue('id',null,'GET');
        if ($aid) {
            // load user with id
            $almanach = $this->entityManager->find('Almanach_Entity_Almanach', $aid);

            if (!$almanach) {
                return LogUtil::registerError($this->__f('Almanach with id %s not found', $aid));
            }
            
            $colors = $this->entityManager->getRepository('Almanach_Entity_Color')->findBy(array('aid'=>$aid));
			$heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$aid));
			
			$hereditySelection = $this->entityManager->getRepository('Almanach_Entity_Almanach')->findBy(array());
			$heredityHide = array();
			foreach($hereditySelection as $key => $item){
				//hide almanach, if there is always a heritage
				$hasalmanach = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$aid, 'caid'=>$item->getAid()));
				if(! empty($hasalmanach) || $item->getAid() == $aid){
					$heredityHide[$key]= true;
				} else {
					$heredityHide[$key]= false;
				}
				
				/*
				* deletes almanach, if it inherits this almanach or if its childs 
				* inherits this almanach. This creates cycles with fatal consequences
				*/
				if(ModUtil::apiFunc('Almanach', 'Heredity', 'isHeredity', array('aid' => $aid, 'paid' => $item->getAid()))){
					unset($hereditySelection[$key]);
				}
				
			}
			
			$groupSelection = $this->entityManager->getRepository('Almanach_Entity_Group')->findBy(array());
			$groupHide = array();
			foreach($groupSelection as $key => $item){
				$hascolor = $this->entityManager->getRepository('Almanach_Entity_Color')->findBy(array('aid'=>$aid, 'gid'=>$item->getGid()));
				if(! empty($hascolor)){
					$groupHide[$key]= true;
				} else {
					$groupHide[$key]= false;
				}
			}
			
        } else {
            $almanach = new Almanach_Entity_Almanach(); 
            $colors = array();
            $heredities = array();   
            $hereditySelection = $this->entityManager->getRepository('Almanach_Entity_Almanach')->findBy(array());   
            $groupSelection = $this->entityManager->getRepository('Almanach_Entity_Group')->findBy(array());
        }
        
        $pullGroups = $this->entityManager->getRepository('Almanach_Entity_Group')->findBy(array());
		$pullGroupSelection = array();
		foreach($pullGroups as $key => $item){
			$pullGroupSelection[] = array(
				'text' => $item->getName(),
				'value' => $item->getGid(),
			);
		}
			
        $googleApiExist = ModUtil::apiFunc('Almanach', 'GoogleCalendarApi', 'apiExist');
    	if($googleApiExist != 0)
    		LogUtil::RegisterStatus($this->__("Google Calendar Api is not installed or configured!"));
    		
		$view->assign('almanach',$almanach);
        $view->assign('colors',$colors);
        $view->assign('googleApiAddress',$this->getVar('googleApiAddress'));
        $view->assign('heredities',$heredities);
        $view->assign('hereditySelection',$hereditySelection);
        $view->assign('heredityHide',$heredityHide);
        $view->assign('groupSelection',$groupSelection);
        $view->assign('pullGroupSelection',$pullGroupSelection);
        $view->assign('pullUserSelection', ModUtil::apiFunc('Almanach', 'Admin', 'getUserList'));
        $view->assign('groupHide',$groupHide);
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
        $url = ModUtil::url('Almanach', 'admin', 'calendar' );
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
        $pushCalendar = $d['ok'];
        unset($d['ok']);
        
        if($aid > 0)
        	$almanach = $this->entityManager->find('Almanach_Entity_Almanach', $aid);
        else
        	$almanach = new Almanach_Entity_Almanach();
        	
        // merge user and save everything
        $almanach->merge($d);
        $this->entityManager->persist($almanach);
        $this->entityManager->flush();
        
        $aid = $almanach->getAid();
        
        //work heredities
        $heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$aid));
        foreach($heredities as $key => $item){
        	$color = FormUtil::getPassedValue('heredityColorinput' . $item->getHid() ,null,'POST');
        	$deleted = FormUtil::getPassedValue('heredityDeleted' . $item->getHid() ,null,'POST');
        	if($deleted){
        		$this->entityManager->remove($item);
				$this->entityManager->flush();
				LogUtil::RegisterStatus($this->__("Heredity has been removed successfully."));
        	} elseif($color != $item->getColor()){
		    	$item->setColor($color);
		    	$this->entityManager->persist($item);
				$this->entityManager->flush();
				LogUtil::RegisterStatus($this->__("Color of Heredity has been updated successfully."));
			}
        }
        
        //create new heredities
        $newHereditiesNum = FormUtil::getPassedValue('newHereditiesNum',null,'POST');
        
        for($i=0; $i < $newHereditiesNum; $i ++){
        	$caid = FormUtil::getPassedValue('newHeredityCaid' . $i ,null,'POST');
        	if($caid == 0)
        		continue;
    		$color = FormUtil::getPassedValue('newHeredityColorinput' . $i ,null,'POST');
        	$heredity = new Almanach_Entity_Heredity();
			$heredity->setCaid($caid);
			$heredity->setPaid($aid);
			$heredity->setColor($color);
			$this->entityManager->persist($heredity);
			$this->entityManager->flush();
			LogUtil::RegisterStatus($this->__("Heredity has been added successfully."));
        }
        
        
        //work colors for groups
        $colors = $this->entityManager->getRepository('Almanach_Entity_Color')->findBy(array('aid'=>$aid));
        foreach($colors as $key => $item){
        	$myColor = FormUtil::getPassedValue('groupColorInput' . $item->getCid() ,null,'POST');
        	$deleted = FormUtil::getPassedValue('colorDeleted' . $item->getCid() ,null,'POST');
        	if($deleted){
        		$this->entityManager->remove($item);
				$this->entityManager->flush();
				LogUtil::RegisterStatus($this->__("Color has been removed successfully."));
        	} elseif($myColor != $item->getColor()){
		    	$item->setColor($myColor);
		    	$this->entityManager->persist($item);
				$this->entityManager->flush();
				LogUtil::RegisterStatus($this->__("Color of a Group has been updated successfully."));
			}
        }
        
        //create new color
        $newColorsNum = FormUtil::getPassedValue('newColorsNum',null,'POST');
        
        for($i=0; $i < $newColorsNum; $i ++){
        	$gid = FormUtil::getPassedValue('newColorGid' . $i ,null,'POST');
        	if($gid == 0)
        		continue;
    		$myColor = FormUtil::getPassedValue('newGroupColorInput' . $i ,null,'POST');
        	$color = new Almanach_Entity_Color();
			$color->setGid($gid);
			$color->setAid($aid);
			$color->setColor($myColor);
			$this->entityManager->persist($color);
			$this->entityManager->flush();
			LogUtil::RegisterStatus($this->__("A color of a group has been added successfully."));
        }

		if($pushCalendar == 'yesButton')
			ModUtil::apiFunc('Almanach', 'GoogleCalendarApi', 'pushCalendar', $almanach->getAid());
        return $view->redirect($url);
    }
}
