<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Enums\PageSlug;
use App\Enums\UserRole;
use App\Filament\Pages\Settings;
use App\Filament\Resources\ActivityLogResource\Pages\CreateActivityLog;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\ContactMessageResource\Pages\ManageContactMessages;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Resources\DonationCampaignResource\Pages\CreateDonationCampaign;
use App\Filament\Resources\InitiativeResource\Pages\CreateInitiative;
use App\Filament\Resources\NewsletterSubscriberResource\Pages\ManageNewsletterSubscribers;
use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Filament\Resources\SiteGalleryResource\Pages\CreateSiteGallery;
use App\Filament\Resources\TrainingResource\Pages\CreateTraining;
use App\Filament\Resources\WorkshopResource\Pages\CreateWorkshop;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\DonationCampaign;
use App\Models\Initiative;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\Setting;
use App\Models\SiteGallery;
use App\Models\Training;
use App\Models\User;
use App\Models\Workshop;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => UserRole::Admin->value, 'is_active' => true]);
        $this->actingAs($this->admin);
    }

    private function category(CategoryType $type): Category
    {
        return Category::create(['name' => 'Dev '.$type->value, 'slug' => 'dev-'.$type->value, 'type' => $type->value]);
    }

    public function test_admin_can_open_every_admin_screen(): void
    {
        $this->seed(PageSeeder::class);

        foreach (['', 'courses', 'workshops', 'trainings', 'initiatives', 'categories', 'donation-campaigns',
            'site-galleries', 'activity-logs', 'pages', 'contact-messages', 'newsletter-subscribers', 'settings'] as $path) {
            $this->get('/admin/'.$path)->assertOk();
        }
    }

    public function test_sidebar_badges_show_counts_and_unread_messages(): void
    {
        $this->category(CategoryType::Course);
        ContactMessage::create(['name' => 'A', 'email' => 'a@example.com', 'message' => 'Hi', 'is_read' => true]);
        ContactMessage::create(['name' => 'B', 'email' => 'b@example.com', 'message' => 'Hi', 'is_read' => false]);
        ContactMessage::create(['name' => 'C', 'email' => 'c@example.com', 'message' => 'Hi', 'is_read' => false]);

        $this->assertSame('1', \App\Filament\Resources\CategoryResource::getNavigationBadge());
        $this->assertSame('0', \App\Filament\Resources\CourseResource::getNavigationBadge());
        $this->assertSame('2', \App\Filament\Resources\ContactMessageResource::getNavigationBadge());
        $this->assertSame('warning', \App\Filament\Resources\ContactMessageResource::getNavigationBadgeColor());

        ContactMessage::query()->update(['is_read' => true]);
        $this->assertSame('3', \App\Filament\Resources\ContactMessageResource::getNavigationBadge());
        $this->assertNull(\App\Filament\Resources\ContactMessageResource::getNavigationBadgeColor());
    }

    public function test_non_admin_users_cannot_open_the_admin_panel(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student->value]);

        $this->actingAs($student)->get('/admin')->assertForbidden();

        $disabledAdmin = User::factory()->create(['role' => UserRole::Admin->value, 'is_active' => false]);

        $this->actingAs($disabledAdmin)->get('/admin')->assertForbidden();
    }

    public function test_admin_created_category_appears_as_course_filter(): void
    {
        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => 'Data Science', 'type' => CategoryType::Course->value])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', ['name' => 'Data Science', 'slug' => 'data-science']);
        $this->get(route('courses.index'))->assertOk()->assertSee('Data Science');
    }

    public function test_admin_can_publish_edit_and_unpublish_a_course(): void
    {
        $category = $this->category(CategoryType::Course);

        Livewire::test(CreateCourse::class)
            ->fillForm([
                'title' => 'Laravel From Zero',
                'short_description' => 'Build real apps',
                'description' => '<p>Full course description</p>',
                'what_you_learn' => "Routing\nEloquent",
                'category_id' => $category->id,
                'instructor_name' => 'Instructor Name',
                'type' => 'online',
                'level' => 'beginner',
                'duration' => 20,
                'price' => 50,
                'thumbnail' => UploadedFile::fake()->image('course.jpg'),
                'status' => 'published',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $course = Course::firstOrFail();
        $this->assertSame('laravel-from-zero', $course->slug);
        Storage::disk('public')->assertExists($course->thumbnail);

        $this->get(route('courses.index'))->assertSee('Laravel From Zero');
        $this->get(route('courses.show', $course))->assertOk()
            ->assertSee('Full course description', false)
            ->assertSee('Eloquent')
            ->assertSee('Instructor Name');

        Livewire::test(EditCourse::class, ['record' => $course->getRouteKey()])
            ->fillForm(['short_description' => 'Updated summary'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('courses.index'))->assertSee('Updated summary');

        Livewire::test(EditCourse::class, ['record' => $course->getRouteKey()])
            ->fillForm(['status' => 'draft'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('courses.index'))->assertDontSee('Laravel From Zero');
        $this->get(route('courses.show', $course))->assertNotFound();
    }

    public function test_admin_can_publish_a_workshop(): void
    {
        $category = $this->category(CategoryType::Workshop);

        Livewire::test(CreateWorkshop::class)
            ->fillForm([
                'title' => 'Docker Hands On',
                'description' => '<p>Containers workshop</p>',
                'instructor_name' => 'Workshop Lead',
                'category_id' => $category->id,
                'type' => 'offline',
                'date' => '2026-12-01',
                'duration' => 3,
                'location' => 'Gaza Hub',
                'price' => 0,
                'status' => 'published',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $workshop = Workshop::firstOrFail();
        $this->get(route('workshops.index'))->assertSee('Docker Hands On');
        $this->get(route('workshops.show', $workshop))->assertOk()->assertSee('Workshop Lead')->assertSee('Gaza Hub');
    }

    public function test_admin_can_publish_a_university_training(): void
    {
        Livewire::test(CreateTraining::class)
            ->fillForm([
                'title' => 'Backend Internship',
                'description' => '<p>Internship details</p>',
                'department' => 'Computer Science',
                'university' => 'Islamic University',
                'available_seats' => 10,
                'start_date' => '2026-12-01',
                'requirements' => 'Know PHP',
                'status' => 'published',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $training = Training::firstOrFail();
        $this->get(route('trainings.index'))->assertSee('Backend Internship')->assertSee('Islamic University');
        $this->get(route('trainings.show', $training))->assertOk()->assertSee('Know PHP');
    }

    public function test_admin_can_publish_an_initiative(): void
    {
        Livewire::test(CreateInitiative::class)
            ->fillForm([
                'title' => 'Code For Gaza',
                'description' => '<p>Community coding</p>',
                'type' => 'community',
                'manager_name' => 'Initiative Manager',
                'start_date' => '2026-12-01',
                'status' => 'published',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $initiative = Initiative::firstOrFail();
        $this->get(route('initiatives.index'))->assertSee('Code For Gaza');
        $this->get(route('initiatives.show', $initiative))->assertOk()->assertSee('Initiative Manager');
    }

    public function test_admin_can_publish_a_donation_campaign(): void
    {
        Livewire::test(CreateDonationCampaign::class)
            ->fillForm([
                'title' => 'Wifi Center',
                'description' => '<p>Keep students online</p>',
                'goal_amount' => 10000,
                'current_amount' => 2500,
                'start_date' => '2026-10-01',
                'status' => 'published',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $campaign = DonationCampaign::firstOrFail();
        $this->get(route('donation.index'))->assertSee('Wifi Center');
        $this->get(route('donation.show', $campaign))->assertOk()->assertSee('Keep students online', false);
    }

    public function test_admin_controls_the_donation_page(): void
    {
        $this->get(route('donation.index'))->assertOk()
            ->assertSee('Active Campaigns')
            ->assertSee('No active campaigns right now.')
            ->assertDontSee('linear-gradient(90deg', false); // default banner from the stylesheet

        Livewire::test(Settings::class)
            ->fillForm([
                'donation_page_title' => 'Support Us',
                'donation_meta_description' => 'Donation SEO text',
                'donation_banner_image' => UploadedFile::fake()->image('banner.jpg', 1600, 500),
                'donation_mission_title' => 'Why It Matters',
                'donation_mission_text' => "Line one\nLine two",
                'donation_campaigns_title' => 'Open Campaigns',
                'donation_campaigns_empty' => 'Nothing open yet',
                'donation_button_text' => 'Give Now',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $banner = Setting::where('key', 'donation_banner_image')->value('value');
        Storage::disk('public')->assertExists($banner);

        $this->get(route('donation.index'))->assertOk()
            ->assertSee('<title>Support Us</title>', false)
            ->assertSee('content="Donation SEO text"', false)
            ->assertSee('storage/'.$banner)
            ->assertSee('Why It Matters')
            ->assertSee('Line one<br />', false)
            ->assertSee('Open Campaigns')
            ->assertSee('Nothing open yet');

        $campaign = DonationCampaign::create([
            'title' => 'Laptops', 'slug' => 'laptops', 'description' => '<p>Rich <strong>text</strong></p>',
            'goal_amount' => 1000, 'current_amount' => 100, 'start_date' => '2026-10-01', 'status' => 'published',
        ]);

        $this->get(route('donation.index'))
            ->assertSee('Give Now')
            ->assertSee('Rich text')            // card preview is plain text
            ->assertDontSee('&lt;p&gt;', false); // no escaped tags shown to visitors

        $this->get(route('donation.show', $campaign))->assertOk()
            ->assertSee('storage/'.$banner)
            ->assertSee('Support Us')
            ->assertSee('Give Now')
            ->assertSee('<strong>text</strong>', false);
    }

    public function test_admin_gallery_and_activities_appear_on_home_page(): void
    {
        Livewire::test(CreateSiteGallery::class)
            ->fillForm([
                'image_path' => UploadedFile::fake()->image('gallery.jpg'),
                'caption' => 'Graduation Day',
                'sort_order' => 1,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        Livewire::test(CreateActivityLog::class)
            ->fillForm([
                'title' => 'Hackathon Weekend',
                'description' => 'Two days of building',
                'type' => 'event',
                'date' => '2026-10-10',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        Storage::disk('public')->assertExists(SiteGallery::firstOrFail()->image_path);

        $this->get(route('home'))->assertOk()
            ->assertSee('Graduation Day')
            ->assertSee('Hackathon Weekend');
    }

    public function test_gallery_strip_shows_every_photo_with_a_count(): void
    {
        $this->get(route('home'))->assertOk()->assertDontSee('Our Gallery');

        foreach (range(1, 10) as $n) {
            SiteGallery::create(['image_path' => "gallery/photo-{$n}.jpg", 'caption' => "Photo {$n}", 'sort_order' => $n]);
        }

        $html = $this->get(route('home'))->assertOk()
            ->assertSee('Our Gallery')
            ->assertSee('10 photos')
            ->assertSee('photo-10.jpg')
            ->getContent();

        $this->assertSame(10, substr_count($html, 'class="gallery-item"'));
        $this->assertStringNotContainsString('back.png', $html);
    }

    public function test_header_has_no_search_or_sign_in(): void
    {
        $this->get(route('home'))->assertOk()
            ->assertDontSee('searchTriggerBtn')
            ->assertDontSee('Sign in')
            ->assertSee('Donate');
    }

    public function test_admin_edits_about_page_content(): void
    {
        $this->seed(PageSeeder::class);
        $page = Page::where('slug', PageSlug::About->value)->firstOrFail();

        Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm(['title' => 'About Jreas', 'content' => '<p>We are an IT cooperative.</p>'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('about'))->assertOk()->assertSee('We are an IT cooperative.', false);
    }

    public function test_admin_controls_home_about_section_and_its_youtube_video(): void
    {
        // Default: no video set, so only a cover image and no play button.
        $this->get(route('home'))->assertOk()
            ->assertSee('About Jreas Hub')
            ->assertDontSee('data-embed=', false);

        Livewire::test(Settings::class)
            ->fillForm(['home_about_video_url' => 'https://example.com/not-youtube'])
            ->call('save')
            ->assertHasFormErrors(['home_about_video_url']);

        Livewire::test(Settings::class)
            ->fillForm([
                'home_about_title' => 'Who We Are',
                'home_about_text' => 'Section text from admin',
                'home_about_button' => 'Read our story',
                'home_about_video_url' => 'https://youtu.be/dQw4w9WgXcQ?si=abc',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('home'))->assertOk()
            ->assertSee('Who We Are')
            ->assertSee('Section text from admin')
            ->assertSee('Read our story')
            ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false)
            ->assertSee('https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg', false);

        Livewire::test(Settings::class)
            ->fillForm(['home_about_video_cover' => UploadedFile::fake()->image('cover.jpg')])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('home'))
            ->assertSee('storage/'.Setting::where('key', 'home_about_video_cover')->value('value'))
            ->assertDontSee('i.ytimg.com', false);
    }

    public function test_admin_settings_control_site_wide_content(): void
    {
        Livewire::test(Settings::class)
            ->fillForm([
                'site_name' => 'Jreas Test Site',
                'site_description' => 'Footer description from admin',
                'facebook_url' => 'https://facebook.com/jreas',
                'contact_email' => 'hello@jreas.test',
                'contact_phone' => '0599000000',
                'contact_address' => 'Gaza, Palestine',
                'donation_url' => 'https://donate.example.com/jreas',
                'donation_mission_title' => 'Why Give',
                'donation_stat_1_number' => '500+',
                'donation_stat_1_label' => 'Students helped',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('home'))->assertOk()
            ->assertSee('Footer description from admin')
            ->assertSee('https://facebook.com/jreas')
            ->assertSee('https://donate.example.com/jreas')
            ->assertSee('hello@jreas.test')
            ->assertSee('0599000000');

        $this->get(route('contact'))->assertSee('Gaza, Palestine');
        $this->get(route('donation.index'))->assertSee('Why Give')->assertSee('500+')->assertSee('Students helped');
    }

    public function test_site_shows_default_text_before_admin_saves_anything(): void
    {
        $this->get(route('home'))->assertOk()
            ->assertSee('Greater Impact')
            ->assertSee('Custom Solutions')
            ->assertSee('Listen, Act, Learn. Repeat')
            ->assertSee('frontend-assets/img/hero.jpg');

        $this->get(route('about'))->assertOk()
            ->assertSee('Empowering Minds, Building Futures')
            ->assertSee('Sustainable career growth');
    }

    public function test_admin_controls_home_and_about_page_content(): void
    {
        Livewire::test(Settings::class)
            ->fillForm([
                'home_hero_badge' => 'Badge From Admin',
                'home_hero_title' => 'Hero Title From Admin',
                'home_hero_highlight' => 'Highlight From Admin',
                'home_hero_text' => 'Hero text from admin',
                'home_hero_image' => UploadedFile::fake()->image('hero.jpg'),
                'home_feature_2_title' => 'Feature Two From Admin',
                'home_about_points' => "Point One\nPoint Two",
                'home_service_workshops_text' => 'Workshops blurb from admin',
                'home_service_courses_image' => UploadedFile::fake()->image('courses.jpg'),
                'home_banner_title' => 'Banner Title From Admin',
                'home_stat_1_number' => '999',
                'home_stat_1_label' => 'Stat label from admin',
                'about_heading' => 'About Heading From Admin',
                'about_points' => 'About point from admin',
                'about_features_title' => 'Why Us From Admin',
                'about_card_3_title' => 'Card Three From Admin',
                'about_image' => UploadedFile::fake()->image('about.jpg'),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $heroImage = Setting::where('key', 'home_hero_image')->value('value');
        Storage::disk('public')->assertExists($heroImage);

        $this->get(route('home'))->assertOk()
            ->assertSee('Badge From Admin')
            ->assertSee('Hero Title From Admin')
            ->assertSee('Highlight From Admin')
            ->assertSee('Hero text from admin')
            ->assertSee('storage/'.$heroImage)
            ->assertSee('Feature Two From Admin')
            ->assertSee('Custom Solutions') // untouched fields keep their default
            ->assertSee('Point Two')
            ->assertSee('Workshops blurb from admin')
            ->assertSee('storage/'.Setting::where('key', 'home_service_courses_image')->value('value'))
            ->assertSee('Banner Title From Admin')
            ->assertSee('Stat label from admin')
            ->assertDontSee('Greater Impact');

        $this->get(route('about'))->assertOk()
            ->assertSee('About Heading From Admin')
            ->assertSee('About point from admin')
            ->assertSee('Why Us From Admin')
            ->assertSee('Card Three From Admin')
            ->assertSee('storage/'.Setting::where('key', 'about_image')->value('value'));
    }

    public function test_admin_seo_favicon_analytics_and_extra_contact_settings_are_used(): void
    {
        $this->seed(PageSeeder::class);
        Page::where('slug', PageSlug::Contact->value)->update(['meta_title' => 'Contact SEO Title']);

        Livewire::test(Settings::class)
            ->fillForm([
                'meta_title' => 'Site SEO Title',
                'meta_description' => 'Site SEO description',
                'google_analytics_id' => 'G-TEST123',
                'favicon' => UploadedFile::fake()->image('favicon.png'),
                'youtube_url' => 'https://youtube.com/@jreas',
                'whatsapp_number' => '+970 599 000 000',
                'contact_address' => 'Gaza, Palestine',
                'google_maps_link' => 'https://maps.google.com/?q=gaza',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->get(route('home'))->assertOk()
            ->assertSee('<title>Site SEO Title</title>', false)
            ->assertSee('content="Site SEO description"', false)
            ->assertSee('gtag/js?id=G-TEST123', false)
            ->assertSee('storage/'.Setting::where('key', 'favicon')->value('value'))
            ->assertSee('https://youtube.com/@jreas')
            ->assertSee('https://wa.me/970599000000')
            ->assertSee('https://maps.google.com/?q=gaza');

        $this->get(route('contact'))->assertSee('<title>Contact SEO Title</title>', false);
    }

    public function test_admin_can_categorise_trainings_for_the_filter(): void
    {
        $category = $this->category(CategoryType::Training);
        $other = Category::create(['name' => 'Other Track', 'slug' => 'other-track', 'type' => CategoryType::Training->value]);

        Livewire::test(CreateTraining::class)
            ->fillForm(['title' => 'Categorised Internship', 'category_id' => $category->id, 'status' => 'published'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame($category->id, Training::firstOrFail()->category_id);
        $this->get(route('trainings.index', ['category' => $category->id]))->assertSee('Categorised Internship');
        $this->get(route('trainings.index', ['category' => $other->id]))->assertDontSee('Categorised Internship');
    }

    public function test_newsletter_signup_reaches_admin_list(): void
    {
        auth()->logout();

        $this->post(route('newsletter.store'), ['newsletter_email' => 'Fan@Example.com'])
            ->assertRedirect()
            ->assertSessionHas('newsletter_status');
        $this->post(route('newsletter.store'), ['newsletter_email' => 'fan@example.com'])->assertRedirect();
        $this->post(route('newsletter.store'), ['newsletter_email' => 'not-an-email'])
            ->assertSessionHasErrors('newsletter_email');

        $this->assertSame(1, NewsletterSubscriber::count());

        $this->actingAs($this->admin);

        Livewire::test(ManageNewsletterSubscribers::class)
            ->assertCanSeeTableRecords(NewsletterSubscriber::all())
            ->assertSee('fan@example.com')
            ->callAction('download')
            ->assertFileDownloaded('newsletter-subscribers.csv');
    }

    public function test_visitor_message_reaches_admin_inbox(): void
    {
        auth()->logout();

        $this->post(route('contact.store'), [
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'message' => 'I want to partner with you',
        ])->assertRedirect();

        $this->actingAs($this->admin);
        $message = ContactMessage::firstOrFail();

        Livewire::test(ManageContactMessages::class)
            ->assertCanSeeTableRecords([$message])
            ->assertSee('Visitor');
    }
}
