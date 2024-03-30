<?php

require_once("./interfaces.php");

abstract class Command implements CommandInterface {
    protected $bot;

    public function __construct(Bot $bot) {
        $this->bot = $bot;
    }
}
