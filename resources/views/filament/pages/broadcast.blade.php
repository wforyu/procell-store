<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-megaphone" class="w-5 h-5 text-amber-500" />
                <span>Buat Broadcast Baru</span>
            </div>
        </x-slot>

        {{ $this->form }}

        <div class="flex justify-end mt-6">
            <x-filament::button wire:click="send" color="primary" icon="heroicon-m-paper-airplane">
                Kirim Broadcast
            </x-filament::button>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Informasi</x-slot>
        <div class="text-sm text-gray-500 space-y-2">
            <p><strong>Email:</strong> Mengirim email ke semua pelanggan. Pastikan konfigurasi SMTP sudah benar di Pengaturan Toko.</p>
            <p><strong>WhatsApp:</strong> Mengirim pesan WhatsApp via Fonnte. Pastikan API Key Fonnte sudah dikonfigurasi.</p>
            <p><strong>Saran:</strong> Gunakan broadcast untuk promo bulanan, pengumuman toko, atau info penting lainnya.</p>
        </div>
    </x-filament::section>
</x-filament-panels::page>
