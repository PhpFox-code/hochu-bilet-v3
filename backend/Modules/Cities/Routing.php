<?php   
    
    return array(
        'backend/cities/index' => 'cities/cities/index',
        'backend/cities/index/page/{page:[0-9]*}' => 'cities/cities/index',
        'backend/cities/edit/{id:[0-9]*}' => 'cities/cities/edit',
        'backend/cities/delete/{id:[0-9]*}' => 'cities/cities/delete',
        'backend/cities/add' => 'cities/cities/add',
    );
