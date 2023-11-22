<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center gap-4 justify-start">
            <x-filament-panels::form.actions :actions="$this->getFormActions()" />

            <x-filament::button type="button" color="gray" tag="a" :href="$this->cancel_button_url">
                Cancel
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
