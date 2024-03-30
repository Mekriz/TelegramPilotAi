<?php

interface CommandInterface {
    public function execute($message);
    public function getName();
}
