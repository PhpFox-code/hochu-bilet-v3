<?php   
    
    return array(
        'backend/afisha/index' => 'afisha/afisha/index',
        'backend/afisha/index/page/{page:[0-9]*}' => 'afisha/afisha/index',
        'backend/afisha/edit/{id:[0-9]*}' => 'afisha/afisha/edit',
        'backend/afisha/delete/{id:[0-9]*}' => 'afisha/afisha/delete',
        'backend/afisha/delete_image/{id:[0-9]*}' => 'afisha/afisha/deleteImage',
        'backend/afisha/add' => 'afisha/afisha/add',
        'backend/afisha/{id:[0-9]*}/printTicket/{key}/{printType}' => 'afisha/afisha/printTicket',
        'backend/afisha/{id:[0-9]*}/createOrder/{key}' => 'afisha/afisha/createOrder',

            // Orders
        'backend/orders/index' => 'afisha/orders/index',
        'backend/orders/index/page/{page:[0-9]*}' => 'afisha/orders/index',
        'backend/orders/edit/{id:[0-9]*}' => 'afisha/orders/edit',
        'backend/orders/delete/{id:[0-9]*}' => 'afisha/orders/delete',
        'backend/orders/print/{id:[0-9]*}' => 'afisha/orders/print',
        'backend/orders/add_position/{id:[0-9]*}' => 'afisha/orders/addPosition',
        'backend/orders/add' => 'afisha/orders/add',

        'backend/afisha_orders/index' => 'afisha/orders/index',
        'backend/afisha_orders/index/page/{page:[0-9]*}' => 'afisha/orders/index',
        'backend/afisha_orders/edit/{id:[0-9]*}' => 'afisha/orders/edit',
        'backend/afisha_orders/delete/{id:[0-9]*}' => 'afisha/orders/delete',
        'backend/afisha_orders/print/{id:[0-9]*}' => 'afisha/orders/print',
        'backend/afisha_orders/add_position/{id:[0-9]*}' => 'afisha/orders/addPosition',
        'backend/afisha_orders/add' => 'afisha/orders/add',
    );