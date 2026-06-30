<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex justify-end mt-6">
        <x-filament::button wire:click="save" type="submit" color="primary">
            Simpan Pengaturan
        </x-filament::button>
    </div>
</x-filament-panels::page>
