@extends('layouts.frontend')

@section('nav-home', 'active')

@push('styles')
  <style>
    .HeroSection {
      position: relative;
      overflow: hidden;
      min-height: 90vh;
      display: flex;
      align-items: center;
      padding-top: 120px;
      padding-bottom: 60px;
    }

    .hero-bg-wrapper {
      position: absolute;
      top: 0;
      right: 0;
      width: 72%;
      height: 100%;
      z-index: 1;
      pointer-events: none;
    }

    .hero-bg-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: 70% 20%;
      -webkit-mask-image: linear-gradient(to left, rgba(0, 0, 0, 1) 50%, rgba(0, 0, 0, 0) 100%),
                          linear-gradient(to bottom, rgba(0, 0, 0, 1) 75%, rgba(0, 0, 0, 0) 100%);
      mask-image: linear-gradient(to left, rgba(0, 0, 0, 1) 50%, rgba(0, 0, 0, 0) 100%),
                  linear-gradient(to bottom, rgba(0, 0, 0, 1) 75%, rgba(0, 0, 0, 0) 100%);
      -webkit-mask-composite: destination-in;
      mask-composite: intersect;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    /* Single video frame in the About section */
    .about-video {
      position: relative;
      width: 100%;
      max-width: 560px;
      margin: 0 auto;
      aspect-ratio: 16 / 9;
      border-radius: 24px;
      overflow: hidden;
      background-color: #0f2f2f;
      outline: 1px solid rgba(20, 102, 102, 0.15);
      outline-offset: 6px;
    }

    .about-video-cover,
    .about-video iframe {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }

    .about-video-cover {
      object-fit: cover;
    }

    @media (max-width: 576px) {
      .about-video {
        border-radius: 18px;
      }
    }

    /* Gallery filmstrip: the strip starts in line with the container and runs off the right edge. */
    .gallery-section {
      --edge: 16px;
      --row: 400px;
      padding: 72px 0 64px;
    }

    @media (min-width: 576px)  { .gallery-section { --edge: calc((100% - 540px) / 2 + 12px); } }
    @media (min-width: 768px)  { .gallery-section { --edge: calc((100% - 720px) / 2 + 12px); } }
    @media (min-width: 992px)  { .gallery-section { --edge: calc((100% - 960px) / 2 + 12px); } }
    @media (min-width: 1200px) { .gallery-section { --edge: calc((100% - 1140px) / 2 + 12px); } }
    @media (min-width: 1400px) { .gallery-section { --edge: calc((100% - 1320px) / 2 + 12px); } }

    .gallery-head {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 24px;
      margin-bottom: 28px;
    }

    .gallery-title {
      margin: 0;
      font-size: clamp(2rem, 1.4rem + 2.4vw, 3.25rem);
      font-weight: 800;
      line-height: 1;
      letter-spacing: -0.03em;
      color: #0b2a2a;
    }

    .gallery-controls {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .gallery-count {
      margin-right: 8px;
      color: #4d6b6b;
      font-size: 0.95rem;
      font-variant-numeric: tabular-nums;
    }

    .gallery-nav {
      width: 48px;
      height: 48px;
      border: 1.5px solid rgba(13, 110, 110, 0.35);
      border-radius: 50%;
      background: transparent;
      color: var(--teal-color);
      transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease;
    }

    .gallery-nav:hover:not(:disabled),
    .gallery-nav:focus-visible {
      background: var(--teal-color);
      border-color: var(--teal-color);
      color: #fff;
    }

    .gallery-nav:focus-visible {
      outline: 3px solid rgba(13, 110, 110, 0.35);
      outline-offset: 2px;
    }

    .gallery-nav:disabled {
      opacity: 0.35;
      cursor: default;
    }

    .gallery-strip {
      overflow-x: auto;
      scroll-snap-type: x proximity;
      scroll-padding-inline: var(--edge);
      overscroll-behavior-x: contain;
      scrollbar-width: none;
    }

    .gallery-strip::-webkit-scrollbar {
      display: none;
    }

    .gallery-strip:focus-visible {
      outline: none;
    }

    .gallery-strip:focus-visible .gallery-track {
      box-shadow: inset 0 -3px 0 var(--teal-color);
    }

    .gallery-track {
      display: flex;
      align-items: flex-start;
      gap: 20px;
      width: max-content;
      margin: 0;
      padding: 0 var(--edge) 4px;
      list-style: none;
    }

    .gallery-item {
      display: block;
      padding: 0;
      border: 0;
      background: none;
      text-align: left;
      cursor: zoom-in;
      scroll-snap-align: start;
    }

    /* Alternating heights and shapes give the strip a walking rhythm instead of a uniform grid. */
    .gallery-frame {
      display: block;
      height: var(--row);
      aspect-ratio: 4 / 5;
      border-radius: 22px;
      overflow: hidden;
      background: #e3eeee;
    }

    .gallery-track li:nth-child(4n + 2) .gallery-frame { aspect-ratio: 3 / 2; height: calc(var(--row) * 0.78); margin-top: calc(var(--row) * 0.22); }
    .gallery-track li:nth-child(4n + 3) .gallery-frame { aspect-ratio: 1; height: calc(var(--row) * 0.86); }
    .gallery-track li:nth-child(4n) .gallery-frame { aspect-ratio: 5 / 4; height: calc(var(--row) * 0.72); margin-top: calc(var(--row) * 0.14); }

    .gallery-frame img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1), filter 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .gallery-item:hover img,
    .gallery-item:focus-visible img {
      transform: scale(1.04);
      filter: saturate(1.1);
    }

    .gallery-item:focus-visible {
      outline: none;
    }

    .gallery-item:focus-visible .gallery-frame {
      outline: 3px solid var(--teal-color);
      outline-offset: 4px;
    }

    .gallery-caption {
      display: block;
      max-width: 32ch;
      margin-top: 12px;
      color: #35504f;
      font-size: 0.95rem;
      font-weight: 500;
      line-height: 1.4;
      transition: color 0.25s ease;
    }

    .gallery-item:hover .gallery-caption {
      color: var(--teal-color);
    }

    .gallery-progress {
      position: relative;
      height: 2px;
      margin-top: 28px;
      background: rgba(13, 110, 110, 0.14);
      border-radius: 2px;
      overflow: hidden;
    }

    .gallery-progress span {
      position: absolute;
      inset: 0;
      background: var(--teal-color);
      transform-origin: left;
      transform: scaleX(var(--progress, 0.1));
      transition: transform 0.15s linear;
    }

    @media (max-width: 767.98px) {
      .gallery-section {
        --row: 300px;
        padding: 48px 0 40px;
      }
      .gallery-track {
        gap: 14px;
      }
      .gallery-frame {
        border-radius: 16px;
      }
      .gallery-nav {
        width: 42px;
        height: 42px;
      }
    }

    @media (max-width: 575.98px) {
      .gallery-section {
        --row: 260px;
      }
      .gallery-count {
        display: none;
      }
      .gallery-track li .gallery-frame {
        margin-top: 0 !important;
      }
    }

    /* Lightbox */
    .lightbox {
      position: fixed;
      inset: 0;
      z-index: 2000;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      padding: 24px;
      background: rgba(5, 22, 22, 0.92);
    }

    .lightbox[hidden] {
      display: none;
    }

    .lightbox-figure {
      margin: 0;
      max-width: min(1100px, 100%);
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .lightbox-figure img {
      max-width: 100%;
      max-height: 78vh;
      object-fit: contain;
      border-radius: 12px;
    }

    .lightbox-figure figcaption {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      color: #e6f2f2;
      font-size: 0.95rem;
    }

    .lightbox-count {
      opacity: 0.7;
      white-space: nowrap;
    }

    .lightbox-btn {
      flex: 0 0 auto;
      width: 48px;
      height: 48px;
      border: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.12);
      color: #fff;
      font-size: 1.1rem;
    }

    .lightbox-btn:hover,
    .lightbox-btn:focus-visible {
      background: var(--teal-color);
    }

    .lightbox-close {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    @media (max-width: 767.98px) {
      .lightbox {
        padding: 70px 12px 24px;
      }
      .lightbox-prev,
      .lightbox-next {
        position: absolute;
        bottom: 24px;
      }
      .lightbox-prev { left: calc(50% - 60px); }
      .lightbox-next { right: calc(50% - 60px); }
    }

    @media (prefers-reduced-motion: reduce) {
      .gallery-frame img,
      .gallery-progress span {
        transition: none;
      }
    }

    @media (max-width: 991px) {
      .hero-bg-wrapper {
        position: relative;
        order: 2;
        width: 100%;
        height: 380px;
        margin-top: 20px;
      }
      .hero-bg-wrapper img {
        -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 60%, rgba(0,0,0,0) 100%);
        mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 60%, rgba(0,0,0,0) 100%);
      }
      .HeroSection {
        flex-direction: column;
        align-items: stretch;
        min-height: auto;
        padding-top: 100px;
        padding-bottom: 0;
      }
    }

    @media (max-width: 575.98px) {
      .hero-bg-wrapper {
        height: 260px;
      }
      .hero-values {
        flex-wrap: wrap;
        row-gap: 12px !important;
      }
    }
  </style>
@endpush

@section('content')
  <!-- hero Section -->
  <section class="HeroSection" id="home">
    <div class="hero-bg-wrapper">
      <img src="{{ !empty($settings['home_hero_image']) ? asset('storage/' . $settings['home_hero_image']) : asset('frontend-assets/img/hero.jpg') }}" alt="Jreas Hub Teamwork & Tech">
    </div>

    <div class="container hero-content">
      <div class="row align-items-center">
        <div class="col-lg-6 col-xl-6">
          <div class="custom-badge d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-3 bg-white shadow-sm">
            <i class="fa-solid fa-users text-teal"></i>
            <span class="fw-semibold text-teal fs-6">{{ $settings['home_hero_badge'] }}</span>
          </div>
          <h1 class="hero-title fw-bold text-dark mb-3 display-4">
            {{ $settings['home_hero_title'] }}
            @if (!empty($settings['home_hero_highlight']))
              <br /><span class="text-teal">{{ $settings['home_hero_highlight'] }}</span>
            @endif
          </h1>
          <p class="hero-text text-muted mb-4 pe-lg-3 fs-5 leading-relaxed">
            {{ $settings['home_hero_text'] }}
          </p>

          <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
            <a href="{{ route('courses.index') }}" class="btn btn-teal px-4 py-3 rounded-pill fw-medium d-inline-flex align-items-center gap-2 shadow-sm">
              Our Services <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-secondary px-4 py-3 rounded-pill fw-medium">
              Partner With Us
            </a>
          </div>

          <div class="hero-values d-flex align-items-center gap-4 text-muted small pt-3 border-top">
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-handshake text-teal fs-5"></i>
              <span class="fw-semibold text-dark">Cooperation</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-lightbulb text-teal fs-5"></i>
              <span class="fw-semibold text-dark">Innovation</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-chart-line text-teal fs-5"></i>
              <span class="fw-semibold text-dark">Growth</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- hero Section -->
  <!-- features -->
  <section class="features py-4">
    <div class="container py-3">
      <div class="row g-3">

        @foreach (['fa-laptop-code', 'fa-network-wired', 'fa-headset', 'fa-rocket'] as $icon)
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="feature-card d-flex align-items-center p-3 rounded-4 shadow-sm h-100 bg-white">
              <div class="icon-wrapper me-3">
                <i class="fa-solid {{ $icon }} text-teal fs-3"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-1 card-title text-dark">{{ $settings['home_feature_' . $loop->iteration . '_title'] ?? '' }}</h6>
                <p class="text-muted small mb-0 card-desc">{{ $settings['home_feature_' . $loop->iteration . '_text'] ?? '' }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  <!-- features -->
  <!--AboutJreas  -->
  <section class="AboutJreas py-5" id="about">
    <div class="container">
    <div class="row align-items-center gy-5">

      <div class="col-lg-6">
        <h2 class="fw-bold text-dark mb-3">{{ $settings['home_about_title'] }}</h2>
        <p class="text-muted mb-4 pe-lg-3 leading-relaxed">{!! nl2br(e($settings['home_about_text'])) !!}</p>

        <div class="row g-3 mb-4">
          @foreach (preg_split('/\R/', $settings['home_about_points'] ?? '', -1, PREG_SPLIT_NO_EMPTY) as $point)
            <div class="col-12 d-flex align-items-center gap-2">
              <i class="fa-solid fa-check text-teal"></i>
              <span class="fw-semibold text-dark small">{{ $point }}</span>
            </div>
          @endforeach
        </div>

        <a href="{{ route('about') }}" class="btn btn-teal px-4 py-2 rounded-pill fw-medium">
          {{ $settings['home_about_button'] }}
        </a>
      </div>

      <div class="col-lg-6">
        @php
          $videoId = \App\Support\YouTube::id($settings['home_about_video_url'] ?? null);
          $videoCover = match (true) {
              !empty($settings['home_about_video_cover']) => asset('storage/' . $settings['home_about_video_cover']),
              $videoId !== null => \App\Support\YouTube::thumbnailUrl($videoId),
              default => asset('frontend-assets/img/service.png'),
          };
        @endphp
        <div class="about-video shadow" id="aboutVideo">
          <img src="{{ $videoCover }}" alt="{{ $settings['home_about_title'] }}" class="about-video-cover" loading="lazy" />
          @if ($videoId)
            <a href="https://www.youtube.com/watch?v={{ $videoId }}" target="_blank" rel="noopener"
               class="play-btn border-0 shadow d-flex align-items-center justify-content-center text-decoration-none"
               data-embed="{{ \App\Support\YouTube::embedUrl($videoId) }}" aria-label="Play video">
              <i class="fa-solid fa-play text-white ms-1"></i>
            </a>
          @endif
        </div>
      </div>

    </div>
  </div>
  </section>
  <!--AboutJreas  -->
  <!--Servicess  -->
  <section class="Servicess py-5">
     <div class="container">
    <h2 class="fw-bold text-dark mb-4">Our services</h2>

    <div class="row g-4">

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="card-img-wrapper">
            <img src="{{ !empty($settings['home_service_courses_image']) ? asset('storage/' . $settings['home_service_courses_image']) : asset('frontend-assets/img/service.png') }}" class="card-img-top" alt="Courses">
          </div>
          <div class="card-body p-3 d-flex flex-column">
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="icon-badge">
                <i class="fa-solid fa-graduation-cap text-teal"></i>
              </div>
              <h6 class="fw-bold mb-0 text-dark">Courses</h6>
            </div>
            <p class="text-muted small mb-3">{{ $settings['home_service_courses_text'] }}</p>

            <div class="mt-auto">
              <a href="{{ route('courses.index') }}" class="learn-more-link text-teal fw-semibold text-decoration-none d-inline-flex align-items-center gap-2">
                Learn more <i class="fa-solid fa-arrow-right-long fs-6"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="card-img-wrapper">
            <img src="{{ !empty($settings['home_service_workshops_image']) ? asset('storage/' . $settings['home_service_workshops_image']) : asset('frontend-assets/img/service.png') }}" class="card-img-top" alt="Workshops">
          </div>
          <div class="card-body p-3 d-flex flex-column">
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="icon-badge">
                <i class="fa-regular fa-lightbulb text-teal"></i>
              </div>
              <h6 class="fw-bold mb-0 text-dark">Hands on Workshops</h6>
            </div>
            <p class="text-muted small mb-3">{{ $settings['home_service_workshops_text'] }}</p>

            <div class="mt-auto">
              <a href="{{ route('workshops.index') }}" class="learn-more-link text-teal fw-semibold text-decoration-none d-inline-flex align-items-center gap-2">
                Learn more <i class="fa-solid fa-arrow-right-long fs-6"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="card-img-wrapper">
            <img src="{{ !empty($settings['home_service_initiatives_image']) ? asset('storage/' . $settings['home_service_initiatives_image']) : asset('frontend-assets/img/service.png') }}" class="card-img-top" alt="Initiatives">
          </div>
          <div class="card-body p-3 d-flex flex-column">
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="icon-badge">
                <i class="fa-solid fa-rocket text-teal"></i>
              </div>
              <h6 class="fw-bold mb-0 text-dark">Initiatives</h6>
            </div>
            <p class="text-muted small mb-3">{{ $settings['home_service_initiatives_text'] }}</p>

            <div class="mt-auto">
              <a href="{{ route('initiatives.index') }}" class="learn-more-link text-teal fw-semibold text-decoration-none d-inline-flex align-items-center gap-2">
                Learn more <i class="fa-solid fa-arrow-right-long fs-6"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="card-img-wrapper">
            <img src="{{ !empty($settings['home_service_trainings_image']) ? asset('storage/' . $settings['home_service_trainings_image']) : asset('frontend-assets/img/service.png') }}" class="card-img-top" alt="University Training">
          </div>
          <div class="card-body p-3 d-flex flex-column">
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="icon-badge">
                <i class="fa-solid fa-user-group text-teal"></i>
              </div>
              <h6 class="fw-bold mb-0 text-dark">University Training</h6>
            </div>
            <p class="text-muted small mb-3">{{ $settings['home_service_trainings_text'] }}</p>

            <div class="mt-auto">
              <a href="{{ route('trainings.index') }}" class="learn-more-link text-teal fw-semibold text-decoration-none d-inline-flex align-items-center gap-2">
                Learn more <i class="fa-solid fa-arrow-right-long fs-6"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  </section>
  <!-- Servicess -->
  <!--  gallery-->
  @if ($galleryImages->isNotEmpty())
  @php
    $photos = $galleryImages->map(fn ($image) => [
        'src' => asset('storage/' . $image->image_path),
        'caption' => (string) $image->caption,
    ])->values();
  @endphp
  <section class="gallery-section" aria-labelledby="galleryTitle">
    <div class="container">
      <div class="gallery-head">
        <h2 id="galleryTitle" class="gallery-title">Our Gallery</h2>
        <div class="gallery-controls">
          <span class="gallery-count">{{ $photos->count() }} {{ \Illuminate\Support\Str::plural('photo', $photos->count()) }}</span>
          <button type="button" class="gallery-nav" data-dir="-1" aria-label="Previous photos" disabled><i class="fa-solid fa-arrow-left"></i></button>
          <button type="button" class="gallery-nav" data-dir="1" aria-label="Next photos"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
      </div>
    </div>

    <div class="gallery-strip" id="galleryStrip" tabindex="0" role="region" aria-label="Photo gallery, scroll sideways">
      <ul class="gallery-track">
        @foreach ($photos as $i => $photo)
          <li>
            <button type="button" class="gallery-item" data-index="{{ $i }}"
                    aria-label="{{ $photo['caption'] !== '' ? $photo['caption'] : 'Open photo ' . ($i + 1) }}">
              <span class="gallery-frame"><img src="{{ $photo['src'] }}" alt="{{ $photo['caption'] }}" loading="lazy"></span>
              @if ($photo['caption'] !== '')
                <span class="gallery-caption">{{ $photo['caption'] }}</span>
              @endif
            </button>
          </li>
        @endforeach
      </ul>
    </div>

    <div class="container">
      <div class="gallery-progress" aria-hidden="true"><span></span></div>
    </div>
  </section>

  <div class="lightbox" id="galleryLightbox" hidden role="dialog" aria-modal="true" aria-label="Gallery">
    <button type="button" class="lightbox-btn lightbox-close" data-action="close" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    <button type="button" class="lightbox-btn lightbox-prev" data-action="prev" aria-label="Previous photo"><i class="fa-solid fa-chevron-left"></i></button>
    <figure class="lightbox-figure">
      <img src="" alt="">
      <figcaption><span class="lightbox-caption"></span><span class="lightbox-count"></span></figcaption>
    </figure>
    <button type="button" class="lightbox-btn lightbox-next" data-action="next" aria-label="Next photo"><i class="fa-solid fa-chevron-right"></i></button>
  </div>
  <script type="application/json" id="galleryPhotos">@json($photos)</script>
  @endif
  <!--  gallery-->
  <!--  activities-->
  <section class="activities-section py-5">
    <div class="container">
      <h2 class="fw-bold text-dark mb-4">Our Activities</h2>

      <div class="row g-4">
        @forelse ($activities as $activity)
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="card activity-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
              <div class="card-img-wrapper">
                <img src="{{ $activity->thumbnail ? asset('storage/' . $activity->thumbnail) : asset('frontend-assets/img/service.png') }}" class="card-img-top" alt="{{ $activity->title }}">
              </div>
              <div class="card-body p-3 d-flex flex-column">
                <div class="mb-2">
                  <span class="badge badge-yellow text-dark-yellow px-2 py-1 rounded-2">{{ ucfirst($activity->type->value) }}</span>
                </div>
                <h6 class="fw-bold card-title text-dark mb-2">
                  {{ $activity->title }}
                </h6>
                <p class="text-muted small card-desc mb-3">
                  {{ \Illuminate\Support\Str::limit($activity->description, 90) }}
                </p>
                <div class="mt-auto d-flex align-items-center gap-2 text-muted extra-small">
                  <i class="fa-regular fa-calendar"></i>
                  <span>{{ $activity->date?->format('F j, Y') }}</span>
                </div>
              </div>
            </div>
          </div>
        @empty
          <p class="text-muted">No activities yet.</p>
        @endforelse
      </div>
    </div>
  </section>
  <!-- activities -->
  <!-- section -->
  <section class="banner-section overflow-hidden position-relative">
    <div class="container-fluid ">
      <div class="row g-0 align-items-stretch">

        <div class="col-lg-6 banner-content-side d-flex align-items-center">
          <div class="container-fluid px-md-5 py-5">
            <div class="banner-text-wrapper ms-lg-auto me-lg-5" style="max-width: 520px;">

              <span class="text-muted small fw-medium mb-2 d-block">{{ $settings['home_banner_label'] }}</span>

              <h2 class="fw-bold text-dark mb-3 banner-heading">
                {{ $settings['home_banner_title'] }}
              </h2>

              <p class="text-muted small mb-4 banner-desc">
                {{ $settings['home_banner_text'] }}
              </p>

              <div class="row g-3 mb-4 max-w-stats">
                @foreach (range(1, 3) as $i)
                  @if (!empty($settings["home_stat_{$i}_number"]))
                    <div class="col-4">
                      <h3 class="fw-bold text-teal mb-0">{{ $settings["home_stat_{$i}_number"] }}</h3>
                      <span class="text-muted extra-small">{{ $settings["home_stat_{$i}_label"] ?? '' }}</span>
                    </div>
                  @endif
                @endforeach
              </div>

              <div>
                <a href="{{ $donateUrl }}" class="btn btn-teal px-4 py-2 rounded-pill fw-medium">
                  Donate
                </a>
              </div>

            </div>
          </div>
        </div>

        <div class="col-lg-6 banner-image-side">
          <img src="{{ !empty($settings['home_banner_image']) ? asset('storage/' . $settings['home_banner_image']) : asset('frontend-assets/img/Frame 1597883364.png') }}" alt="Students learning together" class="img-fluid w-100 h-100 object-fit-cover" />
        </div>

      </div>
    </div>
  </section>
  <!-- section -->
  @include('frontend.partials.contact-section')
@endsection

@section('scripts')
<script>
  // Swap the cover for the YouTube player only when the visitor presses play.
  document.querySelectorAll('#aboutVideo [data-embed]').forEach(function (button) {
    button.addEventListener('click', function (event) {
      event.preventDefault();
      var frame = document.createElement('iframe');
      frame.src = button.dataset.embed;
      frame.title = 'YouTube video';
      frame.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
      frame.allowFullscreen = true;
      button.closest('.about-video').replaceChildren(frame);
    });
  });

  // Gallery lightbox: opens on any tile, arrows/Escape on the keyboard, swipe on phones.
  (function () {
    var box = document.getElementById('galleryLightbox');
    var data = document.getElementById('galleryPhotos');
    if (!box || !data) return;

    var photos = JSON.parse(data.textContent);
    var image = box.querySelector('img');
    var caption = box.querySelector('.lightbox-caption');
    var count = box.querySelector('.lightbox-count');
    var current = 0;
    var opener = null;
    var touchX = null;

    function show(index) {
      current = (index + photos.length) % photos.length;
      image.src = photos[current].src;
      image.alt = photos[current].caption;
      caption.textContent = photos[current].caption;
      count.textContent = (current + 1) + ' / ' + photos.length;
    }

    function open(index, trigger) {
      opener = trigger;
      show(index);
      box.hidden = false;
      document.body.style.overflow = 'hidden';
      box.querySelector('[data-action="close"]').focus();
    }

    function close() {
      box.hidden = true;
      document.body.style.overflow = '';
      if (opener) opener.focus();
    }

    var single = photos.length < 2;
    box.querySelectorAll('[data-action="prev"], [data-action="next"]').forEach(function (button) {
      button.hidden = single;
    });

    document.querySelectorAll('.gallery-item').forEach(function (tile) {
      tile.addEventListener('click', function () {
        open(Number(tile.dataset.index), tile);
      });
    });

    box.addEventListener('click', function (event) {
      var action = event.target.closest('[data-action]');
      if (action) {
        var name = action.dataset.action;
        if (name === 'close') close();
        if (name === 'prev') show(current - 1);
        if (name === 'next') show(current + 1);
      } else if (event.target === box) {
        close();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (box.hidden) return;
      if (event.key === 'Escape') close();
      if (event.key === 'ArrowLeft' && !single) show(current - 1);
      if (event.key === 'ArrowRight' && !single) show(current + 1);
    });

    box.addEventListener('touchstart', function (event) {
      touchX = event.touches[0].clientX;
    }, { passive: true });

    box.addEventListener('touchend', function (event) {
      if (touchX === null || single) return;
      var delta = event.changedTouches[0].clientX - touchX;
      if (Math.abs(delta) > 50) show(current + (delta < 0 ? 1 : -1));
      touchX = null;
    });
  })();

  // Gallery strip: arrow buttons page through it, the line underneath tracks how far along you are.
  (function () {
    var strip = document.getElementById('galleryStrip');
    if (!strip) return;

    var prev = document.querySelector('.gallery-nav[data-dir="-1"]');
    var next = document.querySelector('.gallery-nav[data-dir="1"]');
    var bar = document.querySelector('.gallery-progress span');
    var ticking = false;

    function update() {
      ticking = false;
      var max = strip.scrollWidth - strip.clientWidth;
      var visible = strip.clientWidth / strip.scrollWidth;
      var ratio = max > 0 ? strip.scrollLeft / max : 1;
      bar.style.setProperty('--progress', Math.min(1, visible + (1 - visible) * ratio));
      prev.disabled = strip.scrollLeft <= 2;
      next.disabled = strip.scrollLeft >= max - 2;
    }

    function queue() {
      if (!ticking) {
        ticking = true;
        requestAnimationFrame(update);
      }
    }

    [prev, next].forEach(function (button) {
      button.addEventListener('click', function () {
        var calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        strip.scrollBy({ left: Number(button.dataset.dir) * strip.clientWidth * 0.75, behavior: calm ? 'auto' : 'smooth' });
      });
    });

    strip.addEventListener('scroll', queue, { passive: true });
    window.addEventListener('resize', queue);
    window.addEventListener('load', update);
    update();
  })();
</script>
@endsection
