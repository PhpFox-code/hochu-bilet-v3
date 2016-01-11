<?php   
    
    return array(
        // Callback
        'backend/callback/index' => 'contacts/callback/index',
        'backend/callback/index/page/{page:[0-9]*}' => 'contacts/callback/index',
        'backend/callback/edit/{id:[0-9]*}' => 'contacts/callback/edit',
        'backend/callback/delete/{id:[0-9]*}' => 'contacts/callback/delete',
        // Contacts
        'backend/contacts/index' => 'contacts/contacts/index',
        'backend/contacts/index/page/{page:[0-9]*}' => 'contacts/contacts/index',
        'backend/contacts/edit/{id:[0-9]*}' => 'contacts/contacts/edit',
        'backend/contacts/delete/{id:[0-9]*}' => 'contacts/contacts/delete',
        // Questions
        'backend/questions/index' => 'contacts/questions/index',
        'backend/questions/index/page/{page:[0-9]*}' => 'contacts/questions/index',
        'backend/questions/edit/{id:[0-9]*}' => 'contacts/questions/edit',
        'backend/questions/delete/{id:[0-9]*}' => 'contacts/questions/delete',
    );