<?php
/**
* @package   vroum
* @subpackage vroum
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class produitsCtrl extends jController {
    /**
    *
    */
    function index() {
		 $resp = $this->getResponse('json');

   	     $term = $_GET['term'];

         $cnx  = jDb::getConnection();

         $term2 = ">".implode('>',explode(' ',$term));

         $query = "
                   SELECT * , 
                   			MATCH(nom, long_description)
                   					AGAINST ('".$term."') as Relevance 
                   FROM produits 
                   WHERE 
                   			MATCH (nom, long_description)  AGAINST('".$term2."' IN  BOOLEAN MODE) 
                   GROUP BY nom 
                   ORDER BY Relevance DESC
                   LIMIT 0 , 12";
			   
         $res  = $cnx->query($query);
         $liste = $res->fetchAll();	   
			
	   
	   $data = array();
	   foreach ($liste as $row) {
	      // $row contient un enregistrement
	      $data[] = array( 'id_produit'=> $row->id_produit, 'nom'=> $row->nom ,'prix'=> $row->prix, 'url' => $row->url,
		   			'longimage' => $row->longimage, 'mediumimage' => $row->mediumimage, 'petiteimage' => $row->petiteimage,
					'long_description' => $row->long_description);

	    }		 
		$resp->data = $data;	 
		 return $resp;
    }

    function home() {
    	$resp = $this->getResponse('json');
    
    	jClasses::inc('vroum~shopping');
    	
    	$xmlstring = Shopping::getProductByKeyword($term, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']);
    	
    	$sxe = simplexml_load_string($xmlstring, 'SimpleXMLIterator');
    	
    	if($sxe !== false){
    		$i = 0;
    		foreach(new RecursiveIteratorIterator($sxe, 1) as $name => $data) {
    			if($data->items->offer !== null){
    				foreach($data->items->offer as $d){
    					
    					if(false != (string) $d->imageList->image[4]["available"]) {
    						$liste[$i]['image'] = (string) $d->imageList->image[4]->sourceURL ;
    					} else if (false != (string) $d->imageList->image[3]["available"]) {
    						$liste[$i]['image'] = (string) $d->imageList->image[3]->sourceURL;
    					} else if (false != (string) $d->imageList->image[2]["available"]) {
    						$liste[$i]['image'] = (string) $d->imageList->image[2]->sourceURL ;
    					} else if (false != (string) $d->imageList->image[1]["available"]) {
    						$liste[$i]['image'] = (string) $d->imageList->image[1]->sourceURL ;
    					} else if (false != (string) $d->imageList->image[0]["available"]) {
    						$liste[$i]['image'] = (string) $d->imageList->image[0]->sourceURL ;
    					}
    					$liste[$i]['name'] = (string) $d->name ;
    					$liste[$i]['url'] = (string)  $d->offerURL ;
    					$liste[$i]['prix'] =  (string) $d->basePrice ;
    				$i++;	
    				}
    			}
    				
    		}
    	
    	}		
    
    	$data = array();
    	foreach ($liste as $row) {
    		// $row contient un enregistrement
    		$data[] = array( 'nom'=> $row['name'] ,'prix'=> $row['prix'], 'url' => $row['url'],
    				'longimage' => $row['image'] , 'mediumimage' => $row['image'], 'petiteimage' => $row['image']);
    
    	}
    	$resp->data = $data;
    	return $resp;
    }    
    
    
}
