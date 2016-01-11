<?php
    // Settings of images on the site
    return array(
        // Image types
        'types' => array(
            'jpg', 'jpeg', 'png', 'gif',
        ),
        // Banners images
        'banners' => array(
            array(
                'path' => '',
                'width' => 468,
                'height' => 90,
                'resize' => 1,
                'crop' => 1,
            ),
        ),
        // Slider images
        'slider' => array(
            array(
                'path' => 'small',
                'width' => 200,
                'height' => 70,
                'resize' => 1,
                'crop' => 1,
            ),
            array(
                'path' => 'big',
                'width' => 976,
                'height' => 377,
                'resize' => 1,
                'crop' => 1,
            ),
            // array(
            //     'path' => 'original',
            //     'resize' => 0,
            //     'crop' => 0,
            // ),
        ),
        // Afisha images
        'afisha' => array(
            array(
                'path' => 'medium',
                'width' => 218,
                'height' => 327,
                'resize' => 1,
                'crop' => 1
            ),
        ),
        // News images
        'news' => array(
            array(
                'path' => 'small',
                'width' => 200,
                'height' => 160,
                'resize' => 1,
                'crop' => 1,
            ),
            array(
                'path' => 'big',
                'width' => 600,
                'height' => NULL,
                'resize' => 1,
                'crop' => 0,
            ),
            array(
                'path' => 'original',
                'resize' => 0,
                'crop' => 0,
            ),
        ),
        // Articles images
        'articles' => array(
            array(
                'path' => 'small',
                'width' => 200,
                'height' => 160,
                'resize' => 1,
                'crop' => 1,
            ),
            array(
                'path' => 'big',
                'width' => 600,
                'height' => NULL,
                'resize' => 1,
                'crop' => 0,
            ),
            array(
                'path' => 'original',
                'resize' => 0,
                'crop' => 0,
            ),
        ),
        // Brands images
        'brands' => array(
            array(
                'path' => 'small',
                'width' => NULL,
                'height' => 80,
                'resize' => 1,
                'crop' => 0,
            ),
            array(
                'path' => 'original',
                'resize' => 0,
                'crop' => 0,
            ),
        ),
        // Catalog groups images
        'catalog_tree' => array(
            array(
                'path' => '',
                'width' => 240,
                'height' => 240,
                'resize' => 1,
                'crop' => 1,
            ),
        ),
        // Products images
        'catalog' => array(
            array(
                'path' => 'small',
                'width' => 60,
                'height' => 60,
                'resize' => 1,
                'crop' => 1,
            ),
            array(
                'path' => 'medium',
                'width' => 232,
                'height' => 195,
                'resize' => 1,
                'crop' => 1,
            ),
            array(
                'path' => 'big',
                'width' => 678,
                'height' => 520,
                'resize' => 1,
                'crop' => 0,
            ),
            array(
                'path' => 'original',
                'resize' => 0,
                'crop' => 0,
            ),
        ),
    );