<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

        <h1 class="logo me-auto"><a href="index.html">{{ config('app.name', 'Laravel') }}</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                <li><a class="liens" href="{{ route('Home.index') }}">{{ __('Home') }}</a></li>
                {{-- <li><a class="liens" href="#">About</a></li>
                <li><a class="liens" href="#">{{ __('hel') }}</a></li>
                <li><a class="liens" href="#">Trainers</a></li>
                <li><a class="liens" href="#">Events</a></li>
                <li><a class="liens" href="#">Pricing</a></li>
                <li><a class="liens" href="#">Contact</a></li> --}}
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
</header>
