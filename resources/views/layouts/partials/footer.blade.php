<footer id="page-footer" >
    <!-- SVG centered at the top -->
    <img src="{{ asset('assets/img/skyline.svg') }}" alt="Logo" class="footer-svg">
    <div class="footer-links">
        <a href="{{ route('index') }}"><i class="fas fa-home"></i> Homepage</a>
        <a href="{{ route('page.about') }}"><i class="fas fa-info-circle"></i> About Us</a>
        <a href="{{ route('page.privacy') }}"><i class="fas fa-user-secret"></i> Privacy</a>
        <a href="{{ route('page.terms') }}"><i class="fas fa-file-contract"></i> Terms</a>
        <a href="{{ route('page.disclaimer') }}"><i class="fas fa-sitemap"></i><strong>Disclaimer</strong></a>
        <a href="https://discord.com" target="_blank"><i class="fab fa-discord"></i></a>
        <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
    </div>

    <div class="footer-copyright">
        All Rights Reserved® - [Site Name] - Copyright© <span id="current-year"></span>
    </div>
</footer>
