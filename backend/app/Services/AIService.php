<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private string $model  = 'llama3-70b-8192';

    public function __construct()
    {
        $this->apiKey = config('ai.groq_api_key');
    }

    public function chat(string $userMessage, string $context): array
    {
        $systemPrompt = "Ets un assistent d'anàlisi de dades per a la cafeteria Brew & Co.
            Tens accés a les següents dades actuals del sistema:
            {$context}
            Respon sempre en català, de forma clara i concisa.
            Només pots respondre preguntes relacionades amb les dades de la cafeteria.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, [
            'model'    => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $userMessage],
            ],
            'max_tokens'  => 500,
            'temperature' => 0.5,
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'Error en la connexió amb la IA.',
            ];
        }

        return [
            'success' => true,
            'message' => $response->json('choices.0.message.content'),
        ];
    }
}