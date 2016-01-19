<?php   
    
    return array(
        'backend/cassier/index' => 'statistics/cassier/index',
        'backend/cassier/inner/{id:[0-9]*}' => 'statistics/cassier/inner',
        'backend/cassier/inner/{id:[0-9]*}/page/{page:[0-9]*}' => 'statistics/cassier/inner',
    );