<?php
return array(
    'GET' => array(
        '/' => 'HomeController@index',
        '/home' => 'HomeController@index',
        '/login' => 'AuthController@showLoginForm',
        '/logout' => 'AuthController@logout',
        '/profile' => 'UserController@profile',
        '/settings' => 'UserController@settings',
        '/import-users' => 'ImportUsersController@showImportUsersForm',
        
        // User Management Routes
        '/users' => 'UserManagementController@index',
        '/users/create' => 'UserManagementController@create',
        '/users/edit/{id}' => 'UserManagementController@edit',

        // Product Management Routes
        '/products' => 'ProductManagementController@index',
        '/products/edit/{id}' => 'ProductManagementController@edit',
        
        // Meter Management Routes
        '/meters' => 'MeterManagementController@index',
        '/meters/edit/{id}' => 'MeterManagementController@edit',
        '/meters/get-by-period' => 'MeterManagementController@getByPeriod',
        '/meter-management/get-meter-images' => 'MeterManagementController@getMeterImages', // เพิ่ม route ใหม่นี้


        // Invoice Management Routes
        '/invoices' => 'InvoiceManagementController@index',
        '/invoices/create' => 'InvoiceManagementController@create',
        '/invoices/edit/{id}' => 'InvoiceManagementController@edit',
        '/invoices/get-by-period' => 'InvoiceManagementController@getByPeriod',
        '/invoices/create-invoice' => 'InvoiceManagementController@createInvoice',
        '/invoices/check-invoice' => 'InvoiceManagementController@checkInvoice',

    ),
    'POST' => array(
        '/login' => 'AuthController@login',
        '/language/switch' => 'LanguageController@switchLanguage',
        '/import-users/action' => 'ImportUsersController@importUsersAction',
        
        // User Management Routes
        '/users/store' => 'UserManagementController@store',
        '/users/update/{id}' => 'UserManagementController@update',
        '/users/delete/{id}' => 'UserManagementController@delete',
        '/users/change-status/{id}' => 'UserManagementController@changeStatus',

        // Product Management Routes
        '/products/store' => 'ProductManagementController@store',
        '/products/update/{id}' => 'ProductManagementController@update',

        // Meter Management Routes
        '/meters/store' => 'MeterManagementController@store',
        '/meters/update/{id}' => 'MeterManagementController@update',
        '/meter-management/save-meter' => 'MeterManagementController@saveMeter',

        // Invoice Management Routes
        '/invoices/store' => 'InvoiceManagementController@store',
        '/invoices/update/{id}' => 'InvoiceManagementController@update',
        '/invoices/delete/{id}' => 'InvoiceManagementController@delete',
        '/invoices/get-by-period' => 'InvoiceManagementController@getByPeriod',
        '/invoices/create-invoice' => 'InvoiceManagementController@createInvoice',
        '/invoices/check-invoice' => 'InvoiceManagementController@checkInvoice',

    )
);