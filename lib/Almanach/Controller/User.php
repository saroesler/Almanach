<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class Vermeldungen_Controller_User extends Zikula_AbstractController
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
    	$oid = FormUtil::getPassedValue('oid',0,'GET');
    	$datas = $this->entityManager->getRepository('Vermeldungen_Entity_Data')->findBy(array(),array('ddate'=>'ASC', 'dtime' => 'ASC'));
    	$general = $this->entityManager->getRepository('Vermeldungen_Entity_General')->findBy(array());
    	$i = 0;
    	foreach($datas as $data){
    		if(!$data->hasOutput($oid))
    			unset($datas[$i]);
    		$i ++;
    	}
    	$i = 0;
    	foreach($general as $data){
    		if(!$data->hasOutput($oid))
    			unset($general[$i]);
    		$i ++;
    	}
    	$dates= array();
    	$row =0;
    	$datekey=0;
    	foreach($datas as $data)
    	{
    		if($datekey>0)
    		{
    			if($dates[$datekey-1]['date']!=$data->getDDateFormattedout())
    			{
    				$row++;
    				$dates[] = array("date"=>$data->getDDateFormattedout(),"rows"=>1, "row"=>$row);
    				$datekey++;
    			}
    			else
    				$dates[$datekey-1]['rows']++;
    		}
    		else
			{
				$row++;
				$dates[] = array("date"=>$data->getDDateFormattedout(),"rows"=>1, "row"=>$row);
				$datekey++;
			}
    		$row++;
    	}
    	return $this->view
    		->assign('datas', $datas)
    		->assign('general', $general)
    		->assign('dates', $dates)
    		->fetch('User/view.tpl');
    }
}
