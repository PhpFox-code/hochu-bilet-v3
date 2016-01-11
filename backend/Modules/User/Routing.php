<?php   
    
    return array(
        // Auth
        'backend/auth/login' => 'user/auth/login',
        'backend/auth/edit' => 'user/auth/edit',
        'backend/auth/logout' => 'user/auth/logout',
        // User
        'backend/users/index' => 'user/users/index',
        'backend/users/index/page/{page:[0-9]*}' => 'user/users/index',
        'backend/users/edit/{id:[0-9]*}' => 'user/users/edit',
        'backend/users/delete/{id:[0-9]*}' => 'user/users/delete',
    );
