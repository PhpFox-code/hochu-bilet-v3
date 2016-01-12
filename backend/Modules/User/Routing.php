<?php   
    
    return array(
        // Auth
        'backend/auth/login' => 'user/auth/login',
        'backend/auth/edit' => 'user/auth/edit',
        'backend/auth/logout' => 'user/auth/logout',
        // User
        'backend/users/index' => 'user/users/index',
        'backend/users/index/page/{page:[0-9]*}' => 'user/users/index',
        'backend/users/add' => 'user/users/add',
        'backend/users/edit/{id:[0-9]*}' => 'user/users/edit',
        'backend/users/delete/{id:[0-9]*}' => 'user/users/delete',
        // Admins
        'backend/admins/index' => 'user/admins/index',
        'backend/admins/index/page/{page:[0-9]*}' => 'user/admins/index',
        'backend/admins/add' => 'user/admins/add',
        'backend/admins/edit/{id:[0-9]*}' => 'user/admins/edit',
        'backend/admins/archive/{id:[0-9]*}' => 'user/admins/archive',
        // Roles
        'backend/roles/index' => 'user/roles/index',
        'backend/roles/edit/{id:[0-9]*}' => 'user/roles/edit',
        'backend/roles/add' => 'user/roles/add',
        'backend/roles/delete/{id:[0-9]*}' => 'user/roles/delete',
    );
