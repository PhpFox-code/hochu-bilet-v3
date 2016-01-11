<?php   
    
    return array(
        // Groups
        'backend/groups/index' => 'catalog/groups/index',
        'backend/groups/index/page/{page:[0-9]*}' => 'catalog/groups/index',
        'backend/groups/edit/{id:[0-9]*}' => 'catalog/groups/edit',
        'backend/groups/delete/{id:[0-9]*}' => 'catalog/groups/delete',
        'backend/groups/delete_image/{id:[0-9]*}' => 'catalog/groups/deleteImage',
        'backend/groups/add' => 'catalog/groups/add',
        // Items
        'backend/items/index' => 'catalog/items/index',
        'backend/items/index/page/{page:[0-9]*}' => 'catalog/items/index',
        'backend/items/edit/{id:[0-9]*}' => 'catalog/items/edit',
        'backend/items/delete/{id:[0-9]*}' => 'catalog/items/delete',
        'backend/items/add' => 'catalog/items/add',
        // Brands
        'backend/brands/index' => 'catalog/brands/index',
        'backend/brands/index/page/{page:[0-9]*}' => 'catalog/brands/index',
        'backend/brands/edit/{id:[0-9]*}' => 'catalog/brands/edit',
        'backend/brands/delete/{id:[0-9]*}' => 'catalog/brands/delete',
        'backend/brands/delete_image/{id:[0-9]*}' => 'catalog/brands/deleteImage',
        'backend/brands/add' => 'catalog/brands/add',
        // Models
        'backend/models/index' => 'catalog/models/index',
        'backend/models/index/page/{page:[0-9]*}' => 'catalog/models/index',
        'backend/models/edit/{id:[0-9]*}' => 'catalog/models/edit',
        'backend/models/delete/{id:[0-9]*}' => 'catalog/models/delete',
        'backend/models/add' => 'catalog/models/add',
        // Sizes
        'backend/sizes/index' => 'catalog/sizes/index',
        'backend/sizes/index/page/{page:[0-9]*}' => 'catalog/sizes/index',
        'backend/sizes/edit/{id:[0-9]*}' => 'catalog/sizes/edit',
        'backend/sizes/delete/{id:[0-9]*}' => 'catalog/sizes/delete',
        'backend/sizes/add' => 'catalog/sizes/add',
        // Specifications
        'backend/specifications/index' => 'catalog/specifications/index',
        'backend/specifications/index/page/{page:[0-9]*}' => 'catalog/specifications/index',
        'backend/specifications/edit/{id:[0-9]*}' => 'catalog/specifications/edit',
        'backend/specifications/delete/{id:[0-9]*}' => 'catalog/specifications/delete',
        'backend/specifications/add' => 'catalog/specifications/add',
        // Specifications
        'backend/param/index' => 'catalog/specifications/index',
        'backend/param/index/page/{page:[0-9]*}' => 'catalog/specifications/index',
        'backend/param/edit/{id:[0-9]*}' => 'catalog/specifications/edit',
        'backend/param/delete/{id:[0-9]*}' => 'catalog/specifications/delete',
        'backend/param/add' => 'catalog/specifications/add',
        // ItemsComments
        'backend/comments/index' => 'catalog/comments/index',
        'backend/comments/index/page/{page:[0-9]*}' => 'catalog/comments/index',
        'backend/comments/edit/{id:[0-9]*}' => 'catalog/comments/edit',
        'backend/comments/delete/{id:[0-9]*}' => 'catalog/comments/delete',
    );