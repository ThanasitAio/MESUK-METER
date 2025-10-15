<?php
return [
    'GET' => [
        '/' => 'HomeController@index',
        '/home' => 'HomeController@index',
        '/login' => 'AuthController@showLoginForm',
        '/profile' => 'UserController@profile',
        '/settings' => 'UserController@settings',
        '/import-users' => 'ImportUsersController@showImportUsersForm'
    ],
    'POST' => [
        '/login' => 'AuthController@login',
        '/language/switch' => 'LanguageController@switchLanguage',
        '/import-users/action' => 'ImportUsersController@importUsersAction'
    ]
];