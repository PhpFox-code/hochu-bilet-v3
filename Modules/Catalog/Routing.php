<?php
    
    return array(
        // Catalog unique aliases routing
        'new' => 'catalog/catalog/new',
        'new/page/{page:[0-9]*}' => 'catalog/catalog/new',
        'popular' => 'catalog/catalog/popular',
        'popular/page/{page:[0-9]*}' => 'catalog/catalog/popular',
        'sale' => 'catalog/catalog/sale',
        'sale/page/{page:[0-9]*}' => 'catalog/catalog/sale',
        'viewed' => 'catalog/catalog/viewed',
        'viewed/page/{page:[0-9]*}' => 'catalog/catalog/viewed',
        // Catalog groups routing
        'catalog' => 'catalog/catalog/index',
        'catalog/page/{page:[0-9]*}' => 'catalog/catalog/index',
        'catalog/{alias}' => 'catalog/catalog/groups',
        'catalog/{alias}/page/{page:[0-9]*}' => 'catalog/catalog/groups',
        'catalog/{alias}/filter/{filter}' => 'catalog/catalog/groups',
        'catalog/{alias}/filter/{filter}/page/{page:[0-9]*}' => 'catalog/catalog/groups',
        // Products routing
        'product/{alias}' => 'catalog/product/index',
        // Brands routing
        'brands' => 'catalog/brands/index',
        'brands/{alias}' => 'catalog/brands/inner',
        'brands/{alias}/page/{page:[0-9]*}' => 'catalog/brands/inner',
    );