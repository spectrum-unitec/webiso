<?php

if (!function_exists('nav_active_route')) {
    function nav_active_route(string $name): string
    {
        return service('router')->getMatchedRouteOptions()['as'] === $name
            ? 'active'
            : '';
    }
}