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
import './pjax-script';

// Test jQuery
$(document).ready(function() {
    console.log('jQuery is working!');
});

// Test NProgress
NProgress.start();
setTimeout(() => NProgress.done(), 1000);

// Test Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('testData', () => ({
        message: 'Alpine.js is working!'
    }));
});