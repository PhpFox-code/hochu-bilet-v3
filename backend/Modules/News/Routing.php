<?php   
    
    return array(
        'backend/news/index' => 'news/news/index',
        'backend/news/index/page/{page:[0-9]*}' => 'news/news/index',
        'backend/news/edit/{id:[0-9]*}' => 'news/news/edit',
        'backend/news/delete/{id:[0-9]*}' => 'news/news/delete',
        'backend/news/delete_image/{id:[0-9]*}' => 'news/news/deleteImage',
        'backend/news/add' => 'news/news/add',
    );
