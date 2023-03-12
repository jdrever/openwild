<nav class="navbar navbar-expand-sm navbar-light px-3 py-2">
    <a class="navbar-brand fs-4" href="/">Botanical Records</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-main"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse flex-grow-1 text-right text-white" id="navbar-main">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
            @if (config('core.axiophytesOnly'))
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/axiophytes/">What are Axiophytes?</a>
            </li>
            @endif
            @if (config('core.showSpeciesSearch'))
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/">Species</a>
            </li>
            @endif
            @if (config('core.showSitesSearch'))
            <li class="nav-item">
                <a class="nav-link" href="/sites/">Sites</a>
            </li>
            @endif
            @if (config('core.showSquaresSearch'))
            <li class="nav-item">
                <a class="nav-link" href="/squares/">Squares</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="/about">About</a>
            </li>
        </ul>
    </div>
    </div>
</nav>
