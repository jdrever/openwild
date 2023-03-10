<header>
    <div>
        <a href="/" class="logo"><strong>{{ config('core.siteName') }}</strong></a>
        <a href="#menu" id="menu-toggle" class="menu-toggle" aria-label="Open the menu">
            <svg viewBox="0 0 100 80" width="40" height="40" aria-hidden="true">
                <rect fill="white" width="100" height="15"></rect>
                <rect fill="white" y="30" width="100" height="15"></rect>
                <rect fill="white" y="60" width="100" height="15"></rect>
            </svg>
        </a>
    </div>
    <nav class="main">
        <a id="menu" name="menu"></a>
        <ul class="menu">
            @if (config('core.axiophytesOnly'))
            <li>
                <a href="/axiophytes/">What are Axiophytes?</a>
            </li>
            @endif
            @if (config('core.showSpeciesSearch'))
            <li>
                <a href="/">Species</a>
            </li>
            @endif
            @if (config('core.showSitesSearch'))
            <li>
                <a href="/sites/">Sites</a>
            </li>
            @endif
            @if (config('core.showSquaresSearch'))
            <li>
                <a href="/squares/">Squares</a>
            </li>
            @endif
            <li>
                <a href="/about">About</a>
            </li>
        </ul>
    </nav>
</header>
