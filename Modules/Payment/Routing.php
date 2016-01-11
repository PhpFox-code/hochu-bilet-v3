<?php
    
    return array(
        'payment/{id:[0-9]*}' => 'payment/payment/index',
        'payment/end' => 'payment/payment/end',
        'payment/end/{id:[0-9]*}' => 'payment/payment/end',
    );