<?php

use CodeIgniter\Router\RouteCollection;

service('auth')->routes($routes);

//halaman admin
$routes->group('administrator', ['namespace' => 'App\Controllers\Be', 'filter' => ['auth']], function ($routes) {
    $routes->get('/', 'Home::index', ['as' => 'admin.home']);

    $routes->group('mydocument', function ($routes) {
        $routes->get('/', 'MyDocument::index', ['as' => 'admin.mydoc']);
        $routes->post('ajax-store', 'MyDocument::ajaxStore', ['as' => 'admin.mydocument.store']);
        $routes->get('ajax-data', 'MyDocument::ajaxData', ['as' => 'admin.mydocument.data']);
        $routes->get('ajax-edit/(:num)', 'MyDocument::ajaxEdit/$1', ['as' => 'admin.mydocument.edit']);
        $routes->get('preview/(:any)', 'MyDocument::viewPdf/$1');
        $routes->post('ajax-update', 'MyDocument::ajaxUpdate', ['as' => 'admin.mydocument.update']);
        $routes->delete('ajax-delete/(:num)', 'MyDocument::ajaxDelete/$1', ['as' => 'admin.mydocument.delete']);
        $routes->post('delete-bulk', 'MyDocument::deleteBulk', ['as' => 'admin.mydocument.deleteBulk']);
    });

    $routes->group('master-data', function ($routes) {
        $routes->get('/', 'MasterData::index', ['as' => 'admin.masterdata']);
        $routes->post('store', 'MasterData::store', ['as' => 'admin.masterdata.store']);
    });
});

// Tambah routes untuk Auth
$routes->group('/', ['namespace' => 'App\Controllers\Auth'], function ($routes) {
    // Login
    $routes->get('login', 'AuthenticatedSessionController::new');
    $routes->post('login', 'AuthenticatedSessionController::create');

    // Logout
    $routes->post('logout', 'AuthenticatedSessionController::delete');

    // Register
    $routes->get('register', 'RegisteredUserController::new');
    $routes->post('register', 'RegisteredUserController::create');

    // Forgot Password
    $routes->get('forgot-password', 'PasswordResetLinkController::new');
    $routes->post('forgot-password', 'PasswordResetLinkController::create');

    // Reset Password
    $routes->get('reset-password/(:any)', 'NewPasswordController::create/$1');
    $routes->post('reset-password', 'NewPasswordController::store');

    // Email Verification
    $routes->get('email/verify', 'EmailVerificationPromptController::create');
    $routes->post('email/verification-notification', 'EmailVerificationNotificationController::store');
    $routes->get('email/verify/(:num)/(:any)', 'VerifyEmailController::verify/$1/$2');

    // Confirm Password
    $routes->get('confirm-password', 'ConfirmablePasswordController::show');
    $routes->post('confirm-password', 'ConfirmablePasswordController::store');
});

//halaman-user 
$routes->group('', ['namespace' => 'App\Controllers\Fe'], function ($routes) {
    $routes->get('/', 'Home::index', ['as' => 'home']);
    $routes->get('/pdf/(:num)', 'Home::viewPdf/$1', ['as' => 'pdf']);
    $routes->get('(:segment)', 'Home::menus/$1', ['as' => 'home.menus']);
    $routes->get('(:segment)/(:segment)', 'Home::menus/$1/$2', ['as' => 'home.menus.divisi']);
});
