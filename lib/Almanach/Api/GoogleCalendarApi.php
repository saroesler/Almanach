<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Almanach_Api_GoogleCalendarApi extends Zikula_AbstractApi
{
	/*
	* This function tests, there is a valid googleCalendarApi and all settings are inputed
	* @return: true, if it inherits, false, if there is no heritage
	*/
	public function apiExist()
	{
		if(!file_exists(getcwd().'/modules/Almanach/lib/Almanach/google/almanach.p12'))
			return 1;
		if($this->getVar('googleApiAddress') == '')
			return 2; 
	}
	
	/*
	* This function tests, if the given almanach is inherited by the parent almanach 
	* or of his childs.
	* @param: aid: id of the almanach witch is searched
	* @param: paid: almanach id of the entree point
	* @return: true if there is an heredity, false, if there is no one
	*/
	protected function isHeredityRekrusiv($aid, $paid){
		$heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$paid));
		
		foreach($heredities as $heredity){
			//if the given almanach is child
			if($heredity->getCaid() == $aid)
				return true;
				
			// the cild has a child witch is the given almanach
			if($this->isHeredityRekrusiv($aid, $heredity->getCaid()) == true)
				return true;
		}
		
		return false;
	}
	
	/*
	* This function returns all dates of this almanach and its subalmanachs
	* or of his childs.
	* @param: aid: id of the almanach witch is searched
	* @return: array of dates
	*/
	public function getAllDates($args){
		$aid = $args['aid'];
		$myDates = array();
		
		$almanachs = $this->getAllAlmanachs($aid);
		
		//for each calendar
		foreach($almanachs as $almanach){
			//get dates of calendar
			$dateList = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('aid'=>$almanach->getAid()));
			
			//get real date objects and merge:
			foreach($dateList as $dateElement){
				if($this->arrayHasDate($dateElement->getDid(), $myDates))
					continue;
				$thisdate =  clone $this->entityManager->find('Almanach_Entity_Date', $dateElement->getDid());
				
				if($almanach->getAid() != $aid)
					$thisdate->setNextAlmanach($almanach->getAid());
				else
					$thisdate->setNextAlmanach(0);
				
				//set groupcolor of this almanach
				if($thisdate->getGid() > 0){
					$colors = $this->entityManager->getRepository('Almanach_Entity_Color')->findBy(array('aid'=>$almanach->getAid(), 'gid'=>$thisdate->getGid()));
					
					if(isset($colors[0])){
						if(strlen($colors[0]->getColor()) == 7){
							$thisdate->setColor($colors[0]->getColor());
						}
					}
				}
				
				//get right color:
				if(strlen($almanach->getColor()) == 7){
					$thisdate->setColor($almanach->getColor());
				}
				
				$myDates[] = $thisdate;
			}			
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
		return false;
    }
    
	/*
	* This function returns all almanachs which are part of this almanach
	* or of his childs.
	* @param: aid: id of the almanach witch is searched
	* @return: array of all almanachs
	*/
	public function getAllAlmanachs($aid){
		$almanachs = array();
		$almanachs[] = $this->entityManager->find('Almanach_Entity_Almanach', $aid);
		
		$heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$aid));
		foreach($heredities as $heredity){
			$subalmanachs = $this->getAllAlmanachs($heredity->getCaid());
			if(strlen($heredity->getColor()) == 7){
				foreach($subalmanachs as $item){
					$item->setColor($heredity->getColor());
				}
			}
			$almanachs = array_merge($almanachs, $subalmanachs);
		}
		
		return $almanachs;
	}
	
	/*
	* This function returns an array of all subalmanachs
	* @param: aid: id of the almanach witch is searched
	* @return: array of all almanachs
	*/
	public function getSubCalendar($aid){
		$almanachs = array();
		
		$heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$aid));
		foreach($heredities as $heredity){
			if($heredity->getColor() == '' || $heredity->getColor() == '#')
				$heredity->setColor('#000000');
			$almanachs[] = $this->entityManager->find('Almanach_Entity_Almanach', $heredity->getCaid());
		}
		
		return array('almanach' => $almanachs, 'heredities' => $heredities);
	}
	
	/*
	* This function returns an array of all groups which has dates in the array
	* @param: dates: array of datse
	* @return: array of all groups
	*/
	public function getGroupsOfDates($dates){
		$groups = array();
		$noGroup = false;
		
		
		foreach($dates as $date){
		
			if($date->getGid() > 0){
				if($this->arrayHasGroup($date->getGid(), $groups) == 0)
					$groups[] = $this->entityManager->find('Almanach_Entity_Group', $date->getGid());
			} else
				$noGroup = true;
		}
		
		usort($groups, array("Almanach_Api_Heredity", "groupCmp"));
		
		return array('groups' => $groups, 'noGroup' => $noGroup);
	}
	
	/*
    * This function searches an array, if there is a date
    *  with a given did.
    */
    protected function arrayHasGroup($gid, $groups){
    	foreach($groups as $key => $group){
    		if($group->getGid() == $gid)
    			return $key;
		}
		return false;
    }
    
    public function groupCmp($a, $b)
	{
		return ($a->getName() < $b->getName()) ? -1: 1;
	}
}
