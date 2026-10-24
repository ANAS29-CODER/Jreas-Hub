{{-- Admin-uploaded donation cover; the white fade keeps the title readable on any photo. --}}
@if (!empty($settings['donation_banner_image']))
style="background-image: linear-gradient(90deg, rgba(255, 255, 255, 0.96) 0%, rgba(255, 255, 255, 0.8) 38%, rgba(255, 255, 255, 0) 68%), url('{{ asset('storage/' . $settings['donation_banner_image']) }}'); background-size: cover; background-position: center;"
@endif
