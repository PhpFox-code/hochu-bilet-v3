<?php   
    
    return array(
        // Contact
        'backend/control/contact' => 'content/control/contact',
        // Brone
        'backend/control/brone' => 'content/control/brone',
        // delivery
        'backend/control/delivery' => 'content/control/delivery',
        // Mail director
        'backend/control/mail_director' => 'content/control/mail_director',
        // Index
        'backend/control/index' => 'content/control/index',
        // After payment
        'backend/control/after_payment' => 'content/control/after_payment',
        // Content
        'backend/content/index' => 'content/content/index',
        'backend/content/index/page/{page:[0-9]*}' => 'content/content/index',
        'backend/content/edit/{id:[0-9]*}' => 'content/content/edit',
        'backend/content/delete/{id:[0-9]*}' => 'content/content/delete',
        'backend/content/add' => 'content/content/add',
    );