@extends('layouts.frontend')

@section('title', $course->title)

@section('content')
   <section class="courses-banner ">
      <div class="container">
        <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
          <div class="col-lg-12">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.index') }}" class="text-decoration-none text-muted">Courses</a></li>
                <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">{{ $course->title }}</li>
              </ol>
            </nav>
            <h1>{{ $course->title }}</h1>
            <p class="text-muted">{{ $course->short_description }}</p>
          </div>
        </div>
      </div>
   </section>

   <section class="training-details py-5">
      <div class="container">
        <div class="row g-4 align-items-start">

          <div class="col-lg-8">
            <div class="training-details-overview mb-5">
              <h2 class="training-overview-title fw-bold text-dark mb-3">Course Overview</h2>
              <div class="training-overview-text text-muted lh-lg">
                {!! $course->description !!}
              </div>
            </div>

            @if ($course->what_you_learn)
              <div class="what-you-learn mb-5">
                <h3 class="fw-bold text-dark mb-4">What You'll Learn</h3>
                <div class="row g-3">
                  @foreach (preg_split('/\r\n|\r|\n/', trim($course->what_you_learn)) as $point)
                    @continue(trim($point) === '')
                    <div class="col-md-6 col-sm-6">
                      <div class="training-details-card-item p-4 rounded-4 h-100">
                        <div class="d-flex align-items-center gap-2">
                          <div class="training-icon-wrapper rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0">
                            <i class="fa-solid fa-check training-check-icon"></i>
                          </div>
                          <p class="training-card-text text-muted mb-0">{{ trim($point) }}</p>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            @if ($course->curriculum)
              <div class="training-details-curriculum">
                <h3 class="training-curriculum-title fw-bold text-dark mb-4">Course Curriculum</h3>
                <div class="rounded-4 border p-4 bg-white">
                  {!! $course->curriculum !!}
                </div>
              </div>
            @endif
          </div>

          <div class="col-lg-4">
            <div class="training-details p-3 rounded-4 shadow-sm bg-white border">

              <div class="training-details-img-wrapper position-relative mb-3">
                <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('frontend-assets/img/service.png') }}" alt="{{ $course->title }}" class="img-fluid rounded-4 w-100 object-fit-cover" style="height: 200px;">
              </div>

              <h4 class="fw-bold text-dark fs-5 mb-1">{{ $course->title }}</h4>
              <p class="text-muted small mb-3">{{ $course->instructor_name }}</p>

              <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-success fw-semibold small">{{ ucfirst($course->level->value) }} level</span>
                @if ($course->rating)
                  <div class="d-flex align-items-center gap-1 small fw-semibold">
                    <i class="fa-solid fa-star text-warning"></i>
                    <span class="text-dark">{{ number_format($course->rating, 1) }}</span>
                    <span class="text-muted fw-normal" style="font-size: 0.75rem;">({{ $course->reviews_count }} reviews)</span>
                  </div>
                @else
                  <span class="text-secondary small fw-medium">{{ ucfirst($course->type->value) }}</span>
                @endif
              </div>

              <div class="p-3 mb-3 rounded-3 bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-chair text-muted fs-6"></i>
                  <div>
                    <div class="text-muted" style="font-size: 0.7rem;">Seats</div>
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $course->seats }}</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-regular fa-clock text-muted fs-6"></i>
                  <div>
                    <div class="text-muted" style="font-size: 0.7rem;">Duration</div>
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $course->duration }} hours</div>
                  </div>
                </div>
              </div>

              <div class="p-3 mb-3 rounded-3 bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-regular fa-calendar text-muted fs-6"></i>
                  <div>
                    <div class="text-muted" style="font-size: 0.7rem;">Starts</div>
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $course->start_date?->format('F j, Y') }}</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-tag text-muted fs-6"></i>
                  <div>
                    <div class="text-muted" style="font-size: 0.7rem;">Price</div>
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">${{ number_format((float) $course->price, 2) }}</div>
                  </div>
                </div>
              </div>

              <a href="{{ route('contact') }}" class="btn w-100 text-white rounded-3 py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #146666;">
                Join as student <i class="fa-solid fa-arrow-right"></i>
              </a>

            </div>
          </div>

        </div>
      </div>
    </section>
@endsection
