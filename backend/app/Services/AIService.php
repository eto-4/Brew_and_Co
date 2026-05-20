<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Servei encarregat de la comunicació amb la IA externa.
 *
 * Gestiona les peticions al model d'intel·ligència artificial per
 * respondre consultes relacionades amb les dades de la cafeteria.
 */
class AIService
{
    /**
     * Clau d'autenticació per accedir a l'API de la IA.
     *
     * @var string
     */
    private string $apiKey;

    /**
     * URL de l'endpoint utilitzat per enviar peticions al model de IA.
     *
     * @var string
     */
    private string $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';

    /**
     * Nom del model de llenguatge utilitzat per processar les consultes.
     *
     * @var string
     */
    private string $model  = 'llama-3.3-70b-versatile';

    /**
     * Inicialitza el servei carregant la configuració de l'API.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiKey = config('ai.groq_api_key');
    }

    /**
     * Envia una consulta a la IA amb el context actual del sistema.
     *
     * Genera una conversa amb un prompt de sistema limitat a dades
     * relacionades amb la cafeteria i retorna la resposta generada.
     *
     * @param string $userMessage Missatge enviat per l'usuari.
     * @param string $context Context actual de dades del sistema.
     * @return array{success: bool, message: string}
     */
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