<?php   
    
    return array(
        'backend/slider/index' => 'slider/slider/index',
        'backend/slider/index/page/{page:[0-9]*}' => 'slider/slider/index',
        'backend/slider/edit/{id:[0-9]*}' => 'slider/slider/edit',
        'backend/slider/delete/{id:[0-9]*}' => 'slider/slider/delete',
        'backend/slider/delete_image/{id:[0-9]*}' => 'slider/slider/deleteImage',
        'backend/slider/add' => 'slider/slider/add',
    );
