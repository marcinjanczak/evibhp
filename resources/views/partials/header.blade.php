<header class="site-header">
    <div class="container">
        <div class="logo">
            <a href="/">Logo Firmy</a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Strona główna</a></li>
                <li><a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">O nas</a></li>
                <li><a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Kontakt</a></li>
            </ul>
        </nav>
    </div>
</header>