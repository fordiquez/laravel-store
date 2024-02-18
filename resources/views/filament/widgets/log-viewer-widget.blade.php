<x-filament-widgets::widget class="fi-filament-info-widget">
    <x-filament::section>
        <a href="{{ route('log-viewer.index') }}" class="text-3xl font-bold italic" rel="noopener noreferrer" target="_blank">
            Log Viewer
        </a>

        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            {{ \Composer\InstalledVersions::getPrettyVersion('opcodesio/log-viewer') }}
        </p>
    </x-filament::section>
</x-filament-widgets::widget>
