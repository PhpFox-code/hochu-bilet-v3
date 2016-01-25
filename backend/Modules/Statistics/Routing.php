<?php   
    
    return array(
//        Cashier
        'backend/cassier/index' => 'statistics/cassier/index',
        'backend/cassier/inner/{id:[0-9]*}' => 'statistics/cassier/inner',
        'backend/cassier/inner/{id:[0-9]*}/page/{page:[0-9]*}' => 'statistics/cassier/inner',
//        Organizer
        'backend/organizer/index' => 'statistics/organizer/index',
        'backend/organizer/inner/{id:[0-9]*}' => 'statistics/organizer/inner',
        'backend/organizer/inner/{id:[0-9]*}/page/{page:[0-9]*}' => 'statistics/organizer/inner',
        'backend/organizer/export/{id:[0-9]*}' => 'statistics/organizer/export',
    );