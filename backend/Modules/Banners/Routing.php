<?php   
    
    return array(
        'backend/banners/index' => 'banners/banners/index',
        'backend/banners/index/page/{page:[0-9]*}' => 'banners/banners/index',
        'backend/banners/edit/{id:[0-9]*}' => 'banners/banners/edit',
        'backend/banners/delete/{id:[0-9]*}' => 'banners/banners/delete',
        'backend/banners/delete_image/{id:[0-9]*}' => 'banners/banners/deleteImage',
        'backend/banners/add' => 'banners/banners/add',
    );
