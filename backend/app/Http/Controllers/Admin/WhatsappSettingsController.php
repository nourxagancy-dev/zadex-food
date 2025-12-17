<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhatsappSettingsController extends Controller
{
    private string $path = 'settings/whatsapp.json';

    public function show()
    {
        $settings = $this->loadSettings();
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'recipient' => 'required|string',
            'template' => 'required|string',
            'enabled' => 'boolean'
        ]);

        Storage::disk('local')->put($this->path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return response()->json($data);
    }

    private function loadSettings(): array
    {
        if (!Storage::disk('local')->exists($this->path)) {
            return [
                'recipient' => env('WHATSAPP_NUMBER', ''),
                'template' => config('whatsapp.template'),
                'enabled' => true,
            ];
        }

        return json_decode(Storage::disk('local')->get($this->path), true);
    }
}
