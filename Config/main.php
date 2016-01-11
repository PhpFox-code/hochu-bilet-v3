<?php
    return array(
        'cron' => true, // true, false
        'selfCron' => 10, // Send message after refresh page by users ( false or count of letters for one refresh )
        'tableCron' => 'cron', // Name of the cron table
        
        'image' => 'GD', // GD, Magic

        'password_min_length' => 5, // Min password length
    );