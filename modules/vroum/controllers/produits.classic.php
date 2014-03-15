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

   	     $term = str_replace('-',' ',$_GET['term']);

         $cnx  = jDb::getConnection();

         $term2 = ">".implode(' >',explode(' ',$term));

         $query = "
                   SELECT * , 
                   			MATCH(nom, long_description,categorie_marchand)
                   					AGAINST ('".$term."') as Relevance 
                   FROM produits 
                   WHERE 
                   			MATCH (nom, long_description,categorie_marchand)  AGAINST('".$term2."' IN  BOOLEAN MODE) 
                   GROUP BY nom 
                   ORDER BY Relevance DESC
                   LIMIT 0 , 20";  
         $res  = $cnx->query($query);
         $liste = $res->fetchAll();	   
			
	   
	   $data = array();
	   foreach ($liste as $row) {
	      // $row contient un enregistrement
	      $data[] = array( 'id_produit'=> $row->id_produit, 'nom'=> $row->nom ,'prix'=> $row->prix, 'url' => $row->url,
		   			'longimage' => $row->longimage, 'mediumimage' => $row->mediumimage, 'petiteimage' => $row->petiteimage,
					'long_description' => $row->long_description,'imagecache' => $row->imagecache, 
	      		    'store' => ucfirst($row->boutique) , 'source' => $row->source );
	    }	
		

		$resp->data = $data;	 
		return $resp;
    }
    
    function produitsCategory() {
    	$resp = $this->getResponse('json');
    
   	    $term = str_replace('-',' ',$_GET['term']);
		$offset = $_GET['offset'];
        $cnx  = jDb::getConnection();
        
        $term2 = "+".implode(' +',explode(' ',$term));
    
    	$query = "
    	SELECT * ,
    	MATCH(nom, long_description,categorie_marchand)
    	AGAINST ('".$term."') as Relevance
    	FROM produits
    	WHERE
    	MATCH (nom, long_description,categorie_marchand)  AGAINST('".$term2."' IN  BOOLEAN MODE)
    	GROUP BY nom
    	ORDER BY Relevance DESC
    	LIMIT ".$offset." , 20";

    	$res  = $cnx->query($query);
    	$liste = $res->fetchAll();
    		
    
    	$data = array();
    	foreach ($liste as $row) {
    		// $row contient un enregistrement
    		$data[] = array( 'id_produit'=> $row->id_produit, 'nom'=> $row->nom ,'prix'=> $row->prix, 'url' => $row->url,
    				'longimage' => $row->longimage, 'mediumimage' => $row->mediumimage, 'petiteimage' => $row->petiteimage,
    				'long_description' => $row->long_description,'imagecache' => $row->imagecache,
    				'store' => ucfirst($row->boutique) , 'source' => $row->source );
    	}
    
    
    	$resp->data = $data;
    	return $resp;
    }    

    function home() {
		 $resp = $this->getResponse('json');

   	     $term = 'soutien-gorge';

         $cnx  = jDb::getConnection();

         $term2 = ">".implode('>',explode(' ',$term));

         $query = "
                   SELECT * , 
                   			MATCH(nom, long_description)
                   					AGAINST ('".$term."') as Relevance 
                   FROM produits 
                   WHERE 
                   			MATCH (nom, long_description)  AGAINST('".$term2."' IN  BOOLEAN MODE) 
                   AND imagecache != '' 
                   GROUP BY nom 
                   ORDER BY Relevance DESC 
                   LIMIT 10 , 16";  
         $res  = $cnx->query($query);
         $liste = $res->fetchAll();	   
			
	   
	   $data = array();
	   foreach ($liste as $row) {
	      // $row contient un enregistrement
	      $data[] = array( 'id_produit'=> $row->id_produit, 'nom'=> $row->nom ,'prix'=> $row->prix, 'url' => $row->url,
		   			'longimage' => $row->longimage, 'mediumimage' => $row->mediumimage, 'petiteimage' => $row->petiteimage,
					'long_description' => $row->long_description,'imagecache' => $row->imagecache, 
	      		    'store' => ucfirst($row->boutique) , 'source' => $row->source );
	    }	
		

		$resp->data = $data;
    	return $resp;
    }    
    
    
}
