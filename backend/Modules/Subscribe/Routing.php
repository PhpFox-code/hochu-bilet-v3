<?php   
    
    return array(
        // Subscribe
        'backend/subscribe/index' => 'subscribe/subscribe/index',
        'backend/subscribe/index/page/{page:[0-9]*}' => 'subscribe/subscribe/index',
        'backend/subscribe/send' => 'subscribe/subscribe/send',
        // Subscribers
        'backend/subscribers/index' => 'subscribe/subscribers/index',
        'backend/subscribers/index/page/{page:[0-9]*}' => 'subscribe/subscribers/index',
        'backend/subscribers/edit/{id:[0-9]*}' => 'subscribe/subscribers/edit',
        'backend/subscribers/delete/{id:[0-9]*}' => 'subscribe/subscribers/delete',
        'backend/subscribers/add' => 'subscribe/subscribers/add',
    );
