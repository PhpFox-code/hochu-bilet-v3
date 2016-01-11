<?php 
    return array(
        'afisha' => 'afisha/afisha/index',
        'afisha/page/{page:[0-9]*}' => 'afisha/afisha/index',
        'afisha/{alias}' => 'afisha/afisha/show',
        'afisha/map/{alias}' => 'afisha/afisha/map',
        // 'afisha/{alias}/page/{page:[0-9]*}' => 'afisha/afisha/groups',
        // 'afisha/{alias}/filter/{filter}' => 'afisha/afisha/groups',
        // 'afisha/{alias}/filter/{filter}/page/{page:[0-9]*}' => 'afisha/afisha/groups',
    );