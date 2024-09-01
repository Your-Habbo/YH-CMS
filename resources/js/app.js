import './bootstrap';

import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

import 'flowbite';

import $ from 'jquery';
window.$ = window.jQuery = $;

import Alpine from 'alpinejs';
Alpine.start();
window.Alpine = Alpine;

// Import the PJAX script
import './main';

// Test NProgress
NProgress.start();
setTimeout(() => NProgress.done(), 1000);

