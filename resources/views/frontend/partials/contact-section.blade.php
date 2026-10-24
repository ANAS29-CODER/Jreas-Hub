  <section class="contact-section py-5" id="contact">
    <div class="container">
      <div class="row align-items-stretch">

        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <div>
            <h2 class="fw-bold text-dark mb-3">
              <span class="text-teal">Get In Touch</span> With Our Team
            </h2>
            <p class="text-muted small mb-4 pe-lg-3">
              Have a question or need assistance? Fill out the form below, and our team will get back to you as soon as possible.
            </p>

            <ul class="list-unstyled d-flex flex-column gap-2 mb-4">
              <li class="d-flex align-items-center gap-2 text-dark small fw-medium">
                <i class="fa-solid fa-check text-muted fs-6"></i> Quick response within 24 hours
              </li>
              <li class="d-flex align-items-center gap-2 text-dark small fw-medium">
                <i class="fa-solid fa-check text-muted fs-6"></i> Friendly and dedicated support
              </li>
              <li class="d-flex align-items-center gap-2 text-dark small fw-medium">
                <i class="fa-solid fa-check text-muted fs-6"></i> We reply as soon as possible
              </li>
            </ul>
          </div>

          <div class="row g-3">
            @if (!empty($settings['contact_address']))
              <div class="col-sm-6">
                <div class="contact-info-card p-3 rounded-4">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-location-dot text-teal"></i>
                    <span class="fw-bold text-dark small">Office Location</span>
                  </div>
                  <p class="text-muted extra-small mb-0 ms-4">
                    @if (!empty($settings['google_maps_link']))
                      <a href="{{ $settings['google_maps_link'] }}" target="_blank" rel="noopener" class="text-muted">{{ $settings['contact_address'] }}</a>
                    @else
                      {{ $settings['contact_address'] }}
                    @endif
                  </p>
                </div>
              </div>
            @endif

            @if (!empty($settings['contact_phone']))
              <div class="col-sm-6">
                <div class="contact-info-card p-3 rounded-4">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-phone text-teal"></i>
                    <span class="fw-bold text-dark small">Call Center</span>
                  </div>
                  <p class="text-muted extra-small mb-0 ms-4">{{ $settings['contact_phone'] }}</p>
                </div>
              </div>
            @endif

            @if (!empty($settings['contact_email']))
              <div class="col-sm-6">
                <div class="contact-info-card p-3 rounded-4">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-envelope text-teal"></i>
                    <span class="fw-bold text-dark small">Email</span>
                  </div>
                  <p class="text-muted extra-small mb-0 ms-4">{{ $settings['contact_email'] }}</p>
                </div>
              </div>
            @endif
          </div>

        </div>

        <div class="col-lg-6 row1">
          <div class="contact-form-wrapper p-4 p-md-5 rounded-5 h-100 d-flex flex-column justify-content-center">

            @if (session('status'))
              <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}">
              @csrf

              <div class="mb-3">
                <label class="form-label text-white small mb-1">Full name</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-transparent border-0 text-white-50 pe-0">
                    <i class="fa-solid fa-user"></i>
                  </span>
                  <input type="text" name="name" value="{{ old('name') }}" class="form-control custom-input" placeholder="full name" required>
                </div>
                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3">
                <label class="form-label text-white small mb-1">Email</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-transparent border-0 text-white-50 pe-0">
                    <i class="fa-solid fa-envelope"></i>
                  </span>
                  <input type="email" name="email" value="{{ old('email') }}" class="form-control custom-input" placeholder="Email" required>
                </div>
                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3">
                <label class="form-label text-white small mb-1">Phone number</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-transparent border-0 text-white-50 pe-0">
                    <i class="fa-solid fa-phone"></i>
                  </span>
                  <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control custom-input" placeholder="Phone number">
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label text-white small mb-1">Message</label>
                <textarea name="message" class="form-control custom-input custom-textarea" rows="4" placeholder="Enter message" required>{{ old('message') }}</textarea>
                @error('message')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>

              <button type="submit" class="btn btn-send w-100 rounded-pill py-2 fw-semibold">
                Send
              </button>

            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
