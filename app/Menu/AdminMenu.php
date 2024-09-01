<?php

namespace App\Menu;

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Html;

class AdminMenu
{
    private static $menuItems = [
        ['url' => '/admin/dashboard', 'title' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['url' => '/users', 'title' => 'Users & Roles', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'submenu' => [
            ['url' => '/admin/forum/forum-categories', 'title' => 'Dashboared'],
            ['url' => '/admin/users/', 'title' => 'Manage Users'],
            ['url' => '/admin/roles/', 'title' => 'Manage Roles'],
        ]],
        ['url' => '/radio-schedule', 'title' => 'Radio Schedule', 'icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z'],
        ['url' => '/admin/news', 'title' => 'Articles', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
        ['url' => '/forum', 'title' => 'Forum', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z', 'submenu' => [
            ['url' => '/admin/forum/', 'title' => 'Dashboared'],
            ['url' => '/admin/forum/forum-categories', 'title' => 'Categories'],
            ['url' => '/admin/forum/thread-tags', 'title' => 'Tags'],
        ]],
        ['url' => '/admin/images', 'title' => 'Images', 'icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z'],
        ['url' => '/admin/pages', 'title' => 'Pages', 'icon' => 'M3 3h18v18H3V3zm3 3v12h12V6H6zm6 3h2v6h-2V9zm-4 0h2v6H8V9zm8 0h2v6h-2V9z' ],
        ['url' => '/analytics', 'title' => 'Analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ['url' => '/settings', 'title' => 'Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ];

    public static function build()
    {
        return static::generateMenu();
    }

    private static function generateMenu()
    {
        $menu = Menu::new()->addClass('space-y-2 p-4');

        foreach (self::$menuItems as $item) {
            if (isset($item['submenu'])) {
                $menu->add(static::generateDropdown($item));
            } else {
                $menu->add(static::generateLink($item));
            }
        }

        return $menu->setActiveFromRequest();
    }

    private static function generateLink($item)
    {
        $icon = static::generateIcon($item['icon']);
        $link = Link::to($item['url'], $icon . $item['title'])
            ->addClass('flex items-center py-2 px-4 hover:bg-gray-700 rounded');

        return Html::raw($link);
    }

    private static function generateDropdown($item)
    {
        $icon = static::generateIcon($item['icon']);
        $dropdownHtml = '
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center w-full py-2 px-4 hover:bg-gray-700 rounded">
                    ' . $icon . '
                    ' . $item['title'] . '
                    <svg class="h-5 w-5 ml-auto transform transition-transform duration-200" :class="{\'rotate-180\': open}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <ul x-show="open" @click.away="open = false" x-transition class="bg-gray-700 mt-2 py-2 rounded space-y-1">
        ';
    
        foreach ($item['submenu'] as $subitem) {
            $dropdownHtml .= '
                <li>
                    <a href="' . $subitem['url'] . '" class="block py-2 px-8 hover:bg-gray-600">
                        ' . $subitem['title'] . '
                    </a>
                </li>
            ';
        }
    
        $dropdownHtml .= '
                </ul>
            </div>
        ';
    
        return Html::raw($dropdownHtml);
    }
    
    

    private static function generateIcon($path)
    {
        return '
            <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' . $path . '" />
            </svg>
        ';
    }
}