<?php
return array(
    'GET' => array(
        '/' => 'HomeController@index',
        '/home' => 'HomeController@index',
        '/login' => 'AuthController@showLoginForm',
        '/logout' => 'AuthController@logout',
        '/profile' => 'UserController@profile',
        '/settings' => 'UserController@settings',
        '/import-users' => 'ImportUsersController@showImportUsersForm'
    ),
    'POST' => array(
        '/login' => 'AuthController@login',
        '/language/switch' => 'LanguageController@switchLanguage',
        '/import-users/action' => 'ImportUsersController@importUsersAction'
    )
);