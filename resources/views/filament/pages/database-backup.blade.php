<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between">
                <span>Daftar Backup</span>
            </div>
        </x-slot>

        <div class="text-sm text-gray-500 mb-4">
            <p>Backup database menyimpan seluruh data toko ke dalam file SQL. Gunakan fitur ini secara rutin untuk mencegah kehilangan data.</p>
            <p class="mt-1">Lokasi penyimpanan: <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">storage/app/backups/</code></p>
        </div>

        @if(count($backups) === 0)
            <div class="text-center py-8">
                <x-filament::icon icon="heroicon-m-circle-stack" class="w-12 h-12 mx-auto text-gray-300" />
                <p class="text-gray-500 mt-3">Belum ada backup</p>
                <p class="text-xs text-gray-400 mt-1">Klik "Buat Backup Baru" untuk memulai</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 px-3 font-medium text-gray-500">Nama File</th>
                            <th class="text-right py-2 px-3 font-medium text-gray-500">Ukuran</th>
                            <th class="text-right py-2 px-3 font-medium text-gray-500">Tanggal</th>
                            <th class="text-center py-2 px-3 font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-2.5 px-3 text-gray-900 font-mono text-xs">{{ $backup['name'] }}</td>
                                <td class="py-2.5 px-3 text-right text-gray-600">{{ $backup['size_formatted'] }}</td>
                                <td class="py-2.5 px-3 text-right text-gray-600">{{ $backup['date_formatted'] }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ url('admin/backup/download/'.$backup['name']) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center gap-1">
                                            <x-filament::icon icon="heroicon-m-arrow-down-tray" class="w-4 h-4" /> Download
                                        </a>
                                        <button wire:click="deleteBackup('{{ $backup['name'] }}')" wire:confirm="Hapus backup {{ $backup['name'] }}?" class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center gap-1">
                                            <x-filament::icon icon="heroicon-m-trash" class="w-4 h-4" /> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
