<?php

namespace App\Menu;

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Html;

class MainMenu
{
    private static $menuItems = [
        ['url' => '/', 'title' => 'Home', 'icon' => 'assets/img/icons/32x32.png'],
        ['url' => '/YourHabbo', 'title' => 'YourHabbo', 'icon' => 'assets/img/icons/yb.png', 'submenu' => [
            ['url' => '/about-us', 'title' => 'About Us!', 'icon' => ''],
            ['url' => '/weekly-polls', 'title' => 'Meet the Team', 'icon' => ''],
            ['url' => '/weekly-polls', 'title' => 'Weekly Polls', 'icon' => ''],
        ]],
        ['url' => '', 'title' => 'Radio', 'icon' => 'assets/img/icons/music.png', 'submenu' => [
            ['url' => '/events', 'title' => 'Time Table', 'icon' => ''],
            ['url' => '/events', 'title' => 'Requests', 'icon' => ''],
        ]],
        ['url' => '/forum', 'title' => 'Forum', 'icon' => 'assets/img/icons/forum.png'],
        ['url' => '/join', 'title' => 'Join the Team', 'icon' => 'assets/img/icons/32x32.png'],
        ['url' => '/discord', 'title' => 'Discord', 'icon' => 'assets/img/icons/discord.png'],
    ];

    public static function build()
    {
        return static::generateMenu(false);
    }

    public static function buildMobile()
    {
        return static::generateMenu(true);
    }

    private static function generateMenu($isMobile)
    {
        $menu = Menu::new()->addClass($isMobile ? 'px-2 pt-2 pb-3 space-y-1 sm:px-3' : 'flex custom-space-x-14');

        foreach (self::$menuItems as $item) {
            if (isset($item['submenu'])) {
                $menu->add(static::generateDropdown($item, $isMobile));
            } else {
                $menu->add(static::generateLink($item, $isMobile));
            }
        }

        return $menu->setActiveFromRequest();
    }
    
    private static function generateLink($item, $isMobile)
    {
        $class = $isMobile
            ? 'nav-text block hover:text-gray-300 drop-shadow-2xl uppercase py-12'
            : 'nav-text hover:text-gray-300 drop-shadow-2xl uppercase';

        $iconHtml = '<img src="' . asset($item['icon']) . '" alt="' . $item['title'] . ' icon" class="inline-block w-8 h-8 mr-2">';

        return Link::to($item['url'], $iconHtml . $item['title'])->addClass($class);
    }

    private static function generateDropdown($item, $isMobile)
    {
        $dropdownHtml = $isMobile ? static::mobileDropdown($item) : static::desktopDropdown($item);
        return Html::raw($dropdownHtml);
    }

    private static function desktopDropdown($item)
    {
        $submenuHtml = '';
        foreach ($item['submenu'] as $subitem) {
            $iconHtml = $subitem['icon'] ? '<img src="' . asset($subitem['icon']) . '" alt="' . $subitem['title'] . ' icon" class="inline-block w-8 h-8 mr-2">' : '';
            $submenuHtml .= '
                <a href="' . $subitem['url'] . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    ' . $iconHtml . '<span>' . $subitem['title'] . '</span>
                </a>';
        }

        $iconHtml = '<img src="' . asset($item['icon']) . '" alt="' . $item['title'] . ' icon" class="inline-block w-8 h-8 mr-2">';

        return '
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                <button class="flex items-center text-white hover:text-gray-300 nav-text drop-shadow-2xl uppercase">
                    ' . $iconHtml . '<span>' . $item['title'] . '</span>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{\'rotate-180\': open, \'rotate-0\': !open}" class="inline w-4 h-4 ml-1 transition-transform duration-200 transform">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open"
                     class="absolute left-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-50"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    ' . $submenuHtml . '
                </div>
            </div>
        ';
    }

    private static function mobileDropdown($item)
    {
        $submenuHtml = '';
        foreach ($item['submenu'] as $subitem) {
            $iconHtml = $subitem['icon'] ? '<img src="' . asset($subitem['icon']) . '" alt="' . $subitem['title'] . ' icon" class="inline-block w-8 h-8 mr-2">' : '';
            $submenuHtml .= '
                <a href="' . $subitem['url'] . '" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">
                    ' . $iconHtml . $subitem['title'] . '
                </a>';
        }

        $iconHtml = '<img src="' . asset($item['icon']) . '" alt="' . $item['title'] . ' icon" class="inline-block w-8 h-8 mr-2">';

        return '
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="nav-text block hover:text-gray-300 drop-shadow-2xl uppercase py-2 flex justify-between items-center w-full">
                    ' . $iconHtml . '<span>' . $item['title'] . '</span>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{\'rotate-180\': open, \'rotate-0\': !open}" class="inline w-4 h-4 ml-1 transition-transform duration-200 transform">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" class="mt-2 space-y-2">
                    ' . $submenuHtml . '
                </div>
            </div>
        ';
    }
}