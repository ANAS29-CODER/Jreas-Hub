<?php

namespace App\Filament\Resources\NewsletterSubscriberResource\Pages;

use App\Filament\Resources\NewsletterSubscriberResource;
use App\Models\NewsletterSubscriber;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNewsletterSubscribers extends ManageRecords
{
    protected static string $resource = NewsletterSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->label('تحميل CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => response()->streamDownload(function () {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['email', 'subscribed_at']);

                    NewsletterSubscriber::orderBy('created_at')->each(
                        fn (NewsletterSubscriber $subscriber) => fputcsv($out, [$subscriber->email, $subscriber->created_at])
                    );

                    fclose($out);
                }, 'newsletter-subscribers.csv')),
        ];
    }
}
