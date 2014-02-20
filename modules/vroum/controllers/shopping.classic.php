<?php
/**
* @package   vroum
* @subpackage vroum
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class shoppingCtrl extends jController {
    /**
    *
    */
    function index() {
		$resp = $this->getResponse('html');

   	    $term = $_GET['term'];
   	    jClasses::inc('vroum~shopping');
   	    
   	    $xmlstring = Shopping::getProductByKeyword($term, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']);
   	    
   	    $sxe = simplexml_load_string($xmlstring, 'SimpleXMLIterator'); 
	 
   	    if($sxe !== false){
			foreach(new RecursiveIteratorIterator($sxe, 1) as $name => $data) {				
					if($data->items->offer !== null){
						foreach($data->items->offer as $d){
							echo '<pre>';
								echo (string) $d->name ."<br/>";
							var_dump( (string) $d->offerURL);
							echo '</pre>';
							
							
							echo "---------------------------------------";
						}	
					}
											
			}
		
		}	    
   	    
		return $resp;
    }
}
