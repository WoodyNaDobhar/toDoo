<?php

/*
*
* Copyright(C) 2011+ Woody NaDobhar
*
* class handling efforts related to the location database
*
*/

class Location extends AppModel {
	
	//our vars
	var $name = 'Location';
	public $hasMany = array(
        'MapPoint' => array(
            'className'     => 'MapPoint',
            'foreignKey'    => 'location_id',
            'dependent'     => true
        )
    );
}
?>
