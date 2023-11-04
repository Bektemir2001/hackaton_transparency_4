<?php

namespace App\Services\GPT;
use Illuminate\Support\Facades\Http;

class Gpt4Service
{
    public function gpt_purchase()
    {
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'CONTENT',
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GPT_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post(env('GPT_ENDPOINT'), $data);

        $responseData = $response->json();
        return $responseData;

    }

}
