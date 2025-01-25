<?php

require 'vendor/autoload.php';

use GuzzleHttpClient;

$token = '7669291807:AAF23ZkimqZRDXj0DLLnJpM-aOJ498N9b-c'; // Замените на ваш токен
$apiUrl = "https://api.telegram.org/bot$token/";

$client = new Client();

function sendMessage($chatId, $text) {
    global $client, $apiUrl;
    $client->post($apiUrl . 'sendMessage', [
        'json' => [
            'chat_id' => $chatId,
            'text' => $text,
        ],
    ]);
}

function getHealthyRecipes() {
    return [
        "Салат из киноа: киноа, помидоры, огурцы, оливковое масло, лимонный сок.",
        "Куриная грудка на гриле с овощами: куриная грудка, брокколи, морковь, специи.",
        "Смузи: шпинат, банан, яблоко, миндальное молоко.",
        "Овсянка с ягодами: овсяные хлопья, молоко или вода, свежие ягоды.",
    ];
}

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];

    // Отправляем приветственное сообщение при первом запуске
    if (strtolower($text) === '/start') {
        $welcomeText = "Done! Congratulations on your new bot. You will find it at t.me/fresh_fit_bot. You can now add a description, about section and profile picture for your bot, see /help for a list of commands. By the way, when you've finished creating your cool bot, ping our Bot Support if you want a better username for it. Just make sure the bot is fully operational before you do this.\n\n"
            . "Use this token to access the HTTP API:\n"
            . "$token\n"
            . "Keep your token secure and store it safely, it can be used by anyone to control your bot.\n\n"
            . "For a description of the Bot API, see this page: https://core.telegram.org/bots/api";
        sendMessage($chatId, $welcomeText);
    } elseif (strtolower($text) === 'рецепт') {
        $recipes = getHealthyRecipes();
        $responseText = implode("\n", $recipes);
        sendMessage($chatId, $responseText);
    } else {
        sendMessage($chatId, "Напишите 'Рецепт', чтобы получить здоровые рецепты.");
    }
}

?>
