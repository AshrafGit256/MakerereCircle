<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class QuestionGenerator
{
    public function generateFromContent(string $content, int $count = 10): array
    {
        $content = trim($content);
        $count = max(1, min(25, $count));

        $apiKey = config('services.openai.key');
        if ($apiKey) {
            try {
                $prompt = "Generate {$count} multiple-choice questions (A-D) from the following lecture notes. Return strict JSON array of objects with keys: text, options (A..D), correct (A..D), explanation. Notes:\n\n" . $content;

                $resp = Http::withToken($apiKey)
                    ->timeout(25)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a quiz generator. Output only valid JSON.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.4,
                    ]);

                if ($resp->successful()) {
                    $json = $resp->json('choices.0.message.content');
                    $data = json_decode($json, true);
                    if (is_array($data)) {
                        return array_map(function ($item) {
                            return [
                                'text' => $item['text'] ?? 'What is the key concept?',
                                'options' => $item['options'] ?? [],
                                'correct' => $item['correct'] ?? 'A',
                                'explanation' => $item['explanation'] ?? null,
                                'source' => 'openai',
                            ];
                        }, array_slice($data, 0, $count));
                    }
                }
            } catch (\Throwable $e) {
                // fall back
            }
        }

        // Fallback rule-based generator
        $seed = substr($content, 0, 80) ?: 'lecture';
        $out = [];
        for ($i = 1; $i <= $count; $i++) {
            $opts = [
                'A' => "Concept A{$i}",
                'B' => "Concept B{$i}",
                'C' => "Concept C{$i}",
                'D' => "Concept D{$i}",
            ];
            $keys = array_keys($opts);
            $correct = $keys[array_rand($keys)];
            $out[] = [
                'text' => "({$i}) Based on {$seed} — identify the correct concept.",
                'options' => $opts,
                'correct' => $correct,
                'explanation' => 'From fallback generator.',
                'source' => 'fallback',
            ];
        }
        return $out;
    }
}
