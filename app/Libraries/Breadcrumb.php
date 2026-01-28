<?php

namespace App\Libraries;

class Breadcrumb
{
    public static function generate(array $options = [])
    {
        $uri = service('uri');
        $segments = $uri->getSegments();

        $breadcrumbs = [];

        // Home
        $breadcrumbs[] = [
            'title' => $options['home'] ?? 'HOME',
            'url'   => base_url('/')
        ];

        $path = '';
        foreach ($segments as $segment) {
            $path .= '/' . $segment;

            $breadcrumbs[] = [
                'title' => strtoupper(self::format($segment)),
                'url'   => base_url($path)
            ];
        }

        // Last item not clickable
        $lastIndex = count($breadcrumbs) - 1;
        $breadcrumbs[$lastIndex]['url'] = null;

        return $breadcrumbs;
    }

    protected static function format(string $segment): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $segment));
    }
}
