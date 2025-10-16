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
    )
);