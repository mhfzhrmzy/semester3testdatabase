<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TtsController extends Controller
{
    public function synthesize(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
            'lang' => ['nullable', 'string'],
            'voice' => ['nullable', 'string'],
        ]);

        $text = $validated['text'];
        $lang = $validated['lang'] ?? 'id-ID';
        $voice = $validated['voice'] ?? 'id-ID';

        $cacheKey = 'tts/'.md5($text.$lang.$voice).'.mp3';

        if (Storage::disk('public')->exists($cacheKey)) {
            return response()->file(Storage::disk('public')->path($cacheKey), [
                'Content-Type' => 'audio/mpeg',
            ]);
        }

        $apiKey = config('services.google_tts.key');

        if (! $apiKey) {
            return response()->json([
                'message' => 'Google TTS API key tidak dikonfigurasi.',
            ], 500);
        }

        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
            ])->post('https://texttospeech.googleapis.com/v1/text:synthesize', [
                'input' => ['text' => $text],
                'voice' => ['languageCode' => $lang, 'name' => $voice],
                'audioConfig' => ['audioEncoding' => 'MP3', 'speakingRate' => 1.0],
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'message' => 'Gagal menghasilkan audio.',
                    'error' => $response->body(),
                ], 502);
            }

            $data = $response->json();

            if (! isset($data['audioContent'])) {
                return response()->json([
                    'message' => 'Respons audio kosong.',
                ], 502);
            }

            $audioContent = base64_decode($data['audioContent']);
            Storage::disk('public')->put($cacheKey, $audioContent);

            return response()->file(Storage::disk('public')->path($cacheKey), [
                'Content-Type' => 'audio/mpeg',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghasilkan audio.',
                'error' => $e->getMessage(),
            ], 502);
        }
    }
}
