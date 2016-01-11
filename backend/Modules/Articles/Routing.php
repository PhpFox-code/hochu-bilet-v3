<?php   
    
    return array(
        'backend/articles/index' => 'articles/articles/index',
        'backend/articles/index/page/{page:[0-9]*}' => 'articles/articles/index',
        'backend/articles/edit/{id:[0-9]*}' => 'articles/articles/edit',
        'backend/articles/delete/{id:[0-9]*}' => 'articles/articles/delete',
        'backend/articles/delete_image/{id:[0-9]*}' => 'articles/articles/deleteImage',
        'backend/articles/add' => 'articles/articles/add',
    );