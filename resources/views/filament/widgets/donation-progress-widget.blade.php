<x-filament-widgets::widget>
    <x-filament::section heading="حملات التبرع النشطة">
        <div class="space-y-4">
            @forelse ($campaigns as $campaign)
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium">{{ $campaign['title'] }}</span>
                        <span>{{ number_format($campaign['current_amount'], 2) }} / {{ number_format($campaign['goal_amount'], 2) }}</span>
                    </div>
                    <div class="mt-1 h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                        <div
                            class="h-2 rounded-full bg-primary-500"
                            style="width: {{ $campaign['percentage'] }}%"
                        ></div>
                    </div>
                    <div class="mt-1 text-xs text-gray-500">{{ $campaign['percentage'] }}%</div>
                </div>
            @empty
                <p class="text-sm text-gray-500">لا توجد حملات نشطة حالياً</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
