<?php

require_once("./command.php");

class UnknownCommand extends Command {
    public function execute($message) {
        $chatId = $message['message']['chat']['id'];
        $command = explode(' ', $message['message']['text']);

        $this->bot->sendMessage($chatId, "i don't know \"$command[0]\" command :(");
    }

   public function getName() {
        return '';
   }

}
