<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Messages\MessageKeyboardGenerator;


use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;

class FineMessageKeyboardGenerator
{

    public function generateKeyboard(Fine $fine)
    {

        $previewUrl = UrlProcessor::makeUrl(47, '', '', 'full') . '?' . http_build_query([
                'fine' => $fine->id
            ]);
        $keyboardButtons = [
            ['text' => "📑 Переглянути", 'url' => $previewUrl]
        ];


        if ($fine->canBePaid()) {
            $paymentLink = UrlProcessor::makeUrl(47, '', '', 'full') . '?' . http_build_query([
                    'pay-fine' => $fine->id
                ]);
            $keyboardButtons[] = ['text' => "💳 Сплатити", 'url' => $paymentLink];

        }
        $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
            [
                $keyboardButtons
            ]
        );

        return $keyboard;

    }
}