<nav class="navbar navbar-expand-lg navbar-lf sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            @include('partials.logo')
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav gap-lg-1">
                @auth
                    @if(auth()->user()->isVerified() || auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('items.index','items.show') ? 'active' : '' }}" href="{{ route('items.index') }}">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('items.mine','items.create') ? 'active' : '' }}" href="{{ route('items.mine') }}">My Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('items.create') ? 'active' : '' }}" href="{{ route('items.create') }}">Report Item</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('claims.index','claims.show') ? 'active' : '' }}" href="{{ route('claims.index') }}">My Claims</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('claims.received') ? 'active' : '' }}" href="{{ route('claims.received') }}">Claims on My Items</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('verification.pending') ? 'active' : '' }}" href="{{ route('verification.pending') }}">ID Verification</a>
                        </li>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                        </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-2 ms-lg-auto">
                @guest
                    <li><a href="{{ route('login') }}" class="btn btn-lf-outline btn-sm">Log in</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-lf btn-sm">Sign up</a></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center gap-1" href="{{ route('profile.show') }}">
                            {{ auth()->user()->name }}
                            @include('partials.verified-badge', ['user' => auth()->user(), 'verifiedOnly' => true])
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="btn btn-sm btn-outline-secondary">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
