<?php
    
    return array(
        'user' => 'user/user/index',
        'user/{action}' => 'user/user/{action}',
        'user/orders/id/{id:[0-9]*}' => 'user/user/order',
        'user/print/id/{id:[0-9]*}' => 'user/user/print',
        'user/confirm/hash/{hash}' => 'user/user/confirm'
    );