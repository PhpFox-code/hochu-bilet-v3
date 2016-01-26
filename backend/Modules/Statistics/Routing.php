<?php   
    
    return array(
//        Cashier
        'backend/cassier/index' => 'statistics/cassier/index',
        'backend/cassier/inner/{id:[0-9]*}' => 'statistics/cassier/inner',
        'backend/cassier/inner/{id:[0-9]*}/page/{page:[0-9]*}' => 'statistics/cassier/inner',
        'backend/cassier/export/{user_id:[0-9]*}/{id:[0-9]*}' => 'statistics/cassier/export',

//        Organizer
        'backend/organizer/index' => 'statistics/organizer/index',
        'backend/organizer/inner/{id:[0-9]*}' => 'statistics/organizer/inner',
        'backend/organizer/inner/{id:[0-9]*}/page/{page:[0-9]*}' => 'statistics/organizer/inner',
        'backend/organizer/export/{id:[0-9]*}' => 'statistics/organizer/export',
        'backend/organizer/detailed_export/{id:[0-9]*}' => 'statistics/organizer/detailed_export',
    );