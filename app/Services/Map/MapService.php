<?php

namespace App\Services\Map;

use Illuminate\Support\Facades\Http;

class MapService
{
    // Resolve Google Maps data for a farm location using latitude and longitude.
    public function map($latitude, $longitude): array
    {
        $lat = trim((string) $latitude);
        $lng = trim((string) $longitude);
        $apiKey = (string) config('services.google_maps.key');
        $zoom = (int) config('services.google_maps.zoom', 15);
        $fallbackUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($lat . ',' . $lng);

        if ($lat === '' || $lng === '') {
            return [
                'ok' => false,
                'message' => 'Latitude and longitude are required.',
                'status' => 'MISSING_COORDINATES',
                'address' => null,
                'place_id' => null,
                'google_maps_url' => null,
                'embed_url' => null,
                'static_map_url' => null,
                'raw' => null,
            ];
        }

        if ($apiKey === '') {
            return [
                'ok' => false,
                'message' => 'Google Maps API key is not configured.',
                'status' => 'MISSING_API_KEY',
                'address' => null,
                'place_id' => null,
                'google_maps_url' => $fallbackUrl,
                'embed_url' => null,
                'static_map_url' => null,
                'raw' => null,
            ];
        }

        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'latlng' => $lat . ',' . $lng,
                    'key' => $apiKey,
                ]);
        } catch (\Throwable $exception) {
            return [
                'ok' => false,
                'message' => 'Google Maps lookup failed.',
                'status' => 'REQUEST_FAILED',
                'address' => null,
                'place_id' => null,
                'google_maps_url' => $fallbackUrl,
                'embed_url' => 'https://www.google.com/maps/embed/v1/view?' . http_build_query([
                    'key' => $apiKey,
                    'center' => $lat . ',' . $lng,
                    'zoom' => $zoom,
                ]),
                'static_map_url' => 'https://maps.googleapis.com/maps/api/staticmap?' . http_build_query([
                    'center' => $lat . ',' . $lng,
                    'zoom' => $zoom,
                    'size' => '1200x600',
                    'maptype' => 'roadmap',
                    'markers' => 'color:red|' . $lat . ',' . $lng,
                    'key' => $apiKey,
                ]),
                'raw' => null,
            ];
        }

        $payload = $response->json();
        $status = (string) data_get($payload, 'status', 'UNKNOWN_ERROR');
        $firstResult = data_get($payload, 'results.0', []);

        return [
            'ok' => $response->successful() && in_array($status, ['OK', 'ZERO_RESULTS'], true),
            'message' => $status === 'OK'
                ? 'Map data loaded successfully.'
                : ($status === 'ZERO_RESULTS' ? 'No address details found for these coordinates.' : 'Google Maps lookup failed.'),
            'status' => $status,
            'address' => data_get($firstResult, 'formatted_address'),
            'place_id' => data_get($firstResult, 'place_id'),
            'google_maps_url' => $fallbackUrl,
            'embed_url' => 'https://www.google.com/maps/embed/v1/view?' . http_build_query([
                'key' => $apiKey,
                'center' => $lat . ',' . $lng,
                'zoom' => $zoom,
            ]),
            'static_map_url' => 'https://maps.googleapis.com/maps/api/staticmap?' . http_build_query([
                'center' => $lat . ',' . $lng,
                'zoom' => $zoom,
                'size' => '1200x600',
                'maptype' => 'roadmap',
                'markers' => 'color:red|' . $lat . ',' . $lng,
                'key' => $apiKey,
            ]),
            'raw' => $payload,
        ];
    }
}
