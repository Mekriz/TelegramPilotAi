<?php

require_once("./command.php");

class StartCommand extends Command {
    public function execute($message) {
        $chatId = $message['message']['chat']['id'];
        $command = explode(' ', $message['message']['text']);


        $gen = isset($command[1]) ? $command[1] : "world";
        $text = "Hello, {$gen}!";
        $this->bot->sendMessage($chatId, $text);
    }

   public function getName() {
        return '/start';
   }

}
