<?php
/**
* @package   vroum
* @subpackage vroum
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class categoryCtrl extends jController {
    /**
    *
    */
    function index() {
		 $resp = $this->getResponse('json');
		 
	   // instanciation de la factory
	   $maFactory = jDao::get("category");
	   
   	   $type = $_GET['type'];

	   // récupération d'une liste complète de records de type foo
	   $conditions = jDao::createConditions();
	   $conditions->addCondition('actif','=',1);
	   if($type == 'parent'){
	   		$conditions->addCondition('id_parent','=',0);
	   } elseif ($type == 'child') {
		   $conditions->addCondition('id_parent','!=',0);
	   }
	   	   
	   $conditions->addItemOrder('order','asc');
	   $liste = $maFactory->findBy($conditions);
	   
	   $data = array();
	   foreach ($liste as $row) {
	      // $row contient un enregistrement
	      $data[] = array( 'id_categorie'=> $row->id_categorie, 'id_parent'=> $row->id_parent,
	      		'name_categorie'=> $row->name_categorie, 'tag' => $row->tag );

	   }		 
		$resp->data = $data;	 
		 return $resp;
    }
}
