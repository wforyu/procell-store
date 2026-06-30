<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected string $baseUrl = 'https://api.rajaongkir.com/starter';

    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = Setting::getValue('rajaongkir_api_key');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    public function getCost(int $origin, int $destination, int $weight, string $courier): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        $response = Http::withHeaders(['key' => $this->apiKey])
            ->post($this->baseUrl.'/cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ]);

        if ($response->failed()) {
            return [];
        }

        $body = $response->json();

        return $body['rajaongkir']['results'] ?? [];
    }

    public function getAllCosts(int $origin, int $destination, int $weight): array
    {
        $couriers = ['jne', 'jnt', 'sicepat', 'ninja'];
        $results = [];

        foreach ($couriers as $courier) {
            $costs = $this->getCost($origin, $destination, $weight, $courier);
            foreach ($costs as $result) {
                $services = [];
                foreach ($result['costs'] as $cost) {
                    $services[$cost['service']] = [
                        'service' => $cost['service'],
                        'description' => $cost['description'],
                        'cost' => $cost['cost'][0]['value'],
                        'etd' => $cost['cost'][0]['etd'] ?? '-',
                    ];
                }
                if (! empty($services)) {
                    $results[$result['code']] = [
                        'name' => $result['name'],
                        'services' => $services,
                    ];
                }
            }
        }

        return $results;
    }

    public function getCourierLabel(string $courier): string
    {
        $labels = [
            'jne' => 'JNE',
            'jnt' => 'J&T',
            'sicepat' => 'SiCepat',
            'ninja' => 'Ninja Express',
        ];

        return $labels[$courier] ?? $courier;
    }
}
