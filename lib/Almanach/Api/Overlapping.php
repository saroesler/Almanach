<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Almanach_Api_Overlapping extends Zikula_AbstractApi
{
	/*
	* This function tests, if there is a overlapping of this date to an other one
	* which entered in one calendar witch herits. It tested the given calendar, too.
	* @return: 	array with
	*			'state': 	0, if there is a forbidden overlapping
	*						1, if there is a allowed overlapping 
	*						2, if there is no overlapping
	*			'aid': id of the almanach where the overlapping is
	*			'did': id of the date where the overlapping is
	*/
	public function getOverlappingState($args)
	{
		return $this->findTopLevelAlmanach($args['aid'], $args['did']);
	}
	
	/*
	* This function tests, if there is a overlapping of this date to an other one
	* which entered in one calendar witch herits. It tested the given calendar, too.
	* The funcion went upstairs to the almanach with the highest level and uses the 
	* findOverlaps function to test all almanachs in the tree.
	* @return: 	array with
	*			'state': 	0, if there is a forbidden overlapping
	*						1, if there is a allowed overlapping 
	*						2, if there is no overlapping
	*			'aid': id of the almanach where the overlapping is
	*			'did': id of the date where the overlapping is
	*/
	protected function findTopLevelAlmanach($aid, $did){
		//initialize overlappingstate as all ok
		$overlappingState = 2;
		$overlappingAid = 0;
		$overlappingDid = 0;
		
		/*
		* At first go rekrusiv upstairs to the top level almanachs
		*/
		$heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('caid'=>$aid));
		if(!empty($heredities)){
			//If there is a top parent calendar, go to him
			foreach($heredities as $heredity){
				$myOverlap = $this->findTopLevelAlmanach($heredity->getPaid(), $did);
				
				//Error happens
				if($myOverlap['state'] == 0)
					return $myOverlap;
				
				//merge warnings and oks
				if($myOverlap['state'] < $overlappingState){
					$overlappingState = $myOverlap['state'];
					$overlappingAid = $myOverlap['aid'];
					$overlappingDid = $myOverlap['did'];
				}
			}
			return array('state' => $overlappingState,
						 'aid' => $overlappingAid,
						 'did' => $overlappingDid);
		} else {
			/*
			* go downstairs and look after overlappings
			* overlapping is at first allowed. findOverlaps will set it right
			* for the top level almanach
			*/
			return $this->findOverlaps($aid, $did, 1);
		}
	}
	
	
	/*
	* This function searches downwards all almanachs, if there is an forbidden overlapping.
	* If there is one, it will stop and returns the id of the allmanach.
	* @return: 	array with
	*			'state': 	0, if there is a forbidden overlapping
	*						1, if there is a allowed overlapping 
	*						2, if there is no overlapping
	*			'aid': id of the almanach where the overlapping is
	*			'did': id of the date where the overlapping is
	*/ 
	protected function findOverlaps($aid, $did, $overlappingAllowed){
		//initialize overlappingstate as all ok
		$overlappingState = 2;
		$overlappingAid = 0;
		$overlappingDid = 0;
		$date = $this->entityManager->find('Almanach_Entity_Date', $did);
		
		/*
		* At first look, if this almanach is ok:
		*/
		
		//get all dates of this almanach
		$connections = $this->entityManager->getRepository('Almanach_Entity_AlmanachElement')->findBy(array('aid'=>$aid));
		$almanach = $this->entityManager->find('Almanach_Entity_Almanach', $aid);
		
		//if overlapping was allowed by higher almanachs
		if($overlappingAllowed)
			$overlappingAllowed = $almanach->getOverlapping();
		
		//foreach date
		foreach($connections as $connection){
			if($connection->getDid() == $did)
				continue;
			$testDate = $this->entityManager->find('Almanach_Entity_Date', $connection->getDid());
			
			if($this->isOverlap($testDate, $date)){
				/*
				*	Save the aid for this overlapping
				*/
				$overlappingAid = $almanach->getAid();
				$overlappingDid = $testDate->getDid();
				$overlappingState = 1;
				
				/* 
				* if there is an overlapping, we has to look, if the overlapping is forbidden
				* by an higher almanach, or by this almanach. If it is not alowed, we breaks the
				* rekrusiv loop
				*/
				if(!$overlappingAllowed)
					return array('state' => 0,
								 'aid' => $overlappingAid,
								 'did' => $overlappingDid);
			}
		}
		
		/*
		* If there is all ok, or only a waring, we go downwards
		*/
		$heredities = $this->entityManager->getRepository('Almanach_Entity_Heredity')->findBy(array('paid'=>$aid));
		foreach($heredities as $heredity){
			$myReturns = $this->findOverlaps($heredity->getCaid(), $did, $overlappingAllowed);
			
			//if there is a not allowes overlapping
			if($myReturns['state'] == 0)
				return $myReturns;
			if($myReturns['state'] < $overlappingState){
				$overlappingState = $myReturns['state'];
				$overlappingAid = $myReturns['aid'];
				$overlappingDid = $myReturns['did'];
			}
		}
		
		return array('state' => $overlappingState,
					 'aid' => $overlappingAid,
					 'did' => $overlappingDid);
	}
	
	
	
	/*
	* This function tests two Almanach_Entity_Date objects, if they overlap
	* @return: true, if there is an overlapping
	*/
	protected function isOverlap($firstdate, $seconddate){
		/*
		* There is an overlapping, if the second date begins before the first date begins,
		* but it ends, if the first date has already started
		*/
		if(($seconddate->getStartdate() < $firstdate->getStartdate())
			&& ($seconddate->getEnddate() > $firstdate->getStartdate()))
			return true;
		
		/*
		* There is an overlapping, too, if the second date begins after the first date begins,
		* but it begins, before the first date has already endet
		*/
		if(($seconddate->getStartdate() > $firstdate->getStartdate())
			&& ($seconddate->getStartdate() < $firstdate->getEnddate()))
			return true;
		
		return false;
	}
}
