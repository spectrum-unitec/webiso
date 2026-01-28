<?php

function nav_active_route(string $name): string
{
    return service('router')->getMatchedRouteOptions()['as'] === $name
        ? 'active'
        : '';
}
