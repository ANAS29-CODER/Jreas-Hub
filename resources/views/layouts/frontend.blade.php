<!Doctype html>
<html>

<head>
	<title>@yield('title', $settings['meta_title'] ?? $settings['site_name'])</title>
    <meta charset="utf-8">
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
    <link rel="icon" href="{{ !empty($settings['favicon']) ? asset('storage/' . $settings['favicon']) : asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
     <meta name="viewport"  content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/bootstrap.min.css') }}" type="text/css">
    	<link rel="stylesheet" href="{{ asset('frontend-assets/css/owl.carousel.min.css') }}" type="text/css">

	<link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}" type="text/css">
  @stack('styles')

  @if (preg_match('/^[A-Z0-9-]+$/i', $settings['google_analytics_id'] ?? ''))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics_id'] }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', @json($settings['google_analytics_id']));
    </script>
  @endif
</head>

<body>
  <!-- back to top button-->
	<a class="backTop" href="##">
		<i class="fas fa-arrow-up"></i>
	</a>
  <!-- header Section -->
  <section class="header fixed-top ">
     <div class="container">
        <nav class="navbar navbar-expand-xxl navbar-dark">
          <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
              <img src="{{ !empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('frontend-assets/img/loggggggggggggo.jfif') }}" alt="{{ $settings['site_name'] ?? 'Jreas Coop' }}" height="52" class="d-inline-block align-text-top object-fit-contain">
            </a>
            <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExampleXxl">
              <ul class="navbar-nav m-auto mb-2 mb-xl-0">
                <li class="nav-item">
                  <a class="nav-link @yield('nav-home')" aria-current="page" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link @yield('nav-donation')" href="{{ route('donation.index') }}">Donation</a>
                </li>
                <li class="nav-item dropdown">
                  <a
                    class="nav-link dropdown-toggle custom-dropdown-toggle text-dark"
                    href="#"
                    id="servicesDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    Services
                    <i class="fa-solid fa-chevron-down ms-1 dropdown-icon" style="font-size: 0.8em;"></i>
                  </a>
                  <ul class="dropdown-menu custom-dropdown-menu border-0 shadow-sm rounded-3" aria-labelledby="servicesDropdown">
                    <li><a class="dropdown-item py-2 px-3" href="{{ route('courses.index') }}">Courses</a></li>
                    <li><a class="dropdown-item py-2 px-3" href="{{ route('workshops.index') }}">Workshops</a></li>
                    <li><a class="dropdown-item py-2 px-3" href="{{ route('trainings.index') }}">University Training</a></li>
                    <li><a class="dropdown-item py-2 px-3" href="{{ route('initiatives.index') }}">Initiatives</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="nav-link @yield('nav-about')" href="{{ route('about') }}">About Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link @yield('nav-contact')" href="{{ route('contact') }}">Contact</a>
                </li>

              </ul>
              <div class=" nav-icon d-flex align-items-center justify-content-center gap-3">

                  <!-- lan -->
                  <div class="dropdown language-dropdown">
                    <button class="lang-action-btn border-0 shadow-none dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Change Language">
                      <i class="fa-solid fa-globe"></i>
                    </button>
                    <ul class="dropdown-menu custom-lang-menu border-0 shadow-sm rounded-3 mt-2" aria-labelledby="languageDropdown">
                      <li><a class="dropdown-item py-1 px-2 fs-9" href="#">English</a></li>
                      <li><a class="dropdown-item py-1 px-2 fs-9" href="#">العربية</a></li>
                    </ul>
                  </div>
                  <!-- (Donate Button) -->
                  <a href="{{ $donateUrl }}" class="btn btn-teal px-4 py-2 rounded-pill fw-medium">
                    Donate
                  </a>

                </div>
            </div>
          </div>
        </nav>
     </div>
  </section>
  <!-- header Section -->

  @yield('content')

  <!-- footer -->
  <footer class="footer-section bg-dark-teal text-white pt-5 pb-3">
    <div class="container">
      <div class="row g-3 mb-5">

        <!-- العمود الأول: اللوجو والوصف -->
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand mb-3 d-flex align-items-center gap-2">
            <img src="{{ !empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('frontend-assets/img/loggggggggggggo.jfif') }}" alt="{{ $settings['site_name'] ?? 'Jreas Coop' }}" height="40" class="object-fit-contain">
          </div>
          <p class="text-light-teal small mb-4 pe-lg-3">
            {{ $settings['site_description'] ?? 'Jreas Hub is an IT cooperative & educational hub connecting students, non-profits, and businesses to create a better world.' }}
          </p>
          <!-- وسائل التواصل الاجتماعي -->
          <div class="d-flex gap-2">
            @if (!empty($settings['facebook_url']))
              <a href="{{ $settings['facebook_url'] }}" target="_blank" class="social-icon-btn"><i class="fa-brands fa-facebook-f"></i></a>
            @endif
            @if (!empty($settings['twitter_url']))
              <a href="{{ $settings['twitter_url'] }}" target="_blank" class="social-icon-btn"><i class="fa-brands fa-x-twitter"></i></a>
            @endif
            @if (!empty($settings['instagram_url']))
              <a href="{{ $settings['instagram_url'] }}" target="_blank" class="social-icon-btn"><i class="fa-brands fa-instagram"></i></a>
            @endif
            @if (!empty($settings['linkedin_url']))
              <a href="{{ $settings['linkedin_url'] }}" target="_blank" class="social-icon-btn"><i class="fa-brands fa-linkedin-in"></i></a>
            @endif
            @if (!empty($settings['youtube_url']))
              <a href="{{ $settings['youtube_url'] }}" target="_blank" class="social-icon-btn"><i class="fa-brands fa-youtube"></i></a>
            @endif
            @if (!empty($settings['whatsapp_number']))
              <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp_number']) }}" target="_blank" class="social-icon-btn"><i class="fa-brands fa-whatsapp"></i></a>
            @endif
          </div>
        </div>

        <!-- العمود الثاني: روابط سريعة -->
        <div class="col-lg-2 col-md-6 col-6">
          <h5 class="fw-bold mb-3 text-white fs-6">Quick Links</h5>
          <ul class="list-unstyled footer-links small">
            <li class="mb-2"><a href="{{ route('home') }}">Home</a></li>
            <li class="mb-2"><a href="{{ route('about') }}">About Us</a></li>
            <li class="mb-2"><a href="{{ route('donation.index') }}">Donations</a></li>
            <li class="mb-2"><a href="{{ route('courses.index') }}">Courses</a></li>
            <li class="mb-2"><a href="{{ route('contact') }}">Contact</a></li>
          </ul>
        </div>

        <!-- العمود الثالث: حملات التبرع / الدعم -->
        <div class="col-lg-2 col-md-6 col-6">
          <h5 class="fw-bold mb-3 text-white fs-6">Get Involved</h5>
          <ul class="list-unstyled footer-links small">
            <li class="mb-2"><a href="#">Privacy Policy</a></li>
            <li class="mb-2"><a href="#">Terms of Service</a></li>
          </ul>
        </div>

        <!-- العمود الرابع: النشرة البريدية -->
        <div class="col-lg-4 col-md-6">
          <h5 class="fw-bold mb-3 text-white fs-6">Newsletter</h5>
          <p class="text-light-teal small mb-3">
            Subscribe to get updates on new campaigns and global impact stories.
          </p>
          @if (session('newsletter_status'))
            <div class="alert alert-success py-2 small">{{ session('newsletter_status') }}</div>
          @endif
          <form method="POST" action="{{ route('newsletter.store') }}" class="newsletter-form position-relative" id="newsletter">
            @csrf
            <div class="input-group">
              <input type="email" name="newsletter_email" value="{{ old('newsletter_email') }}" class="form-control rounded-pill pe-5 py-2 small" placeholder="Enter your email" required>
              <button class="btn btn-teal rounded-pill position-absolute end-0 top-0 bottom-0 m-1 px-3" type="submit">
                Subscribe
              </button>
            </div>
            @error('newsletter_email')<div class="text-warning small mt-1">{{ $message }}</div>@enderror
          </form>
        </div>

      </div>

      <hr class="border-secondary opacity-25 my-4">

      <!-- شريط الحقوق السفلي -->
      <div class="row align-items-center small text-light-teal">
        <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
          &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Jreas Coop' }}. All rights reserved.
        </div>
      </div>
    </div>
  </footer>
  <!-- footer -->
	<script src="{{ asset('frontend-assets/js/jquery3.3.1.js') }}"></script>
	<script src="{{ asset('frontend-assets/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('frontend-assets/js/owl.carousel.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
</body>

</html>
