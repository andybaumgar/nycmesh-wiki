<?php

use BookStack\Auth\User;
use BookStack\Facades\Theme;
use BookStack\Theming\ThemeEvents;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

Theme::listen(ThemeEvents::WEB_MIDDLEWARE_BEFORE, function (Request $request) {

    $allowPathsPrefixes = ['login', 'password'];
    $path = $request->path();
    $ip = $request->ip();

    $restrictedLoginPath = true;
    foreach ($allowPathsPrefixes as $pathPrefix) {
        if (strpos($path, $pathPrefix) === 0) {
            $restrictedLoginPath = false;
        }
    }

    $hasMeshIP = false;
    if (strpos($ip, '10.') === 0) {
        $hasMeshIP = true;
    }

    // non mesh ip trying to login 
    if ($restrictedLoginPath && !$hasMeshIP) {
        throw new AccessDeniedHttpException('Access denied');
    }

    return null;
});
