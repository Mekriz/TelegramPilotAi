<?php

class Bot {
    private $token;
    private $commands = [];

    public function __construct($token) {
        $this->token = $token;
        $this->registerCommands();
    }

    private function registerCommands() {
        $files = scandir('cmds/');
        foreach ($files as $file) {
            if (is_file('cmds/'. $file)) {
                $className = basename($file, '.php');
                require_once 'cmds/'. $file;
                $cmd = new $className($this);
                $this->commands[$cmd->getName()] = $cmd;
            }
        }
    }

    public function handle($update) {
        if (isset($update['message']['text'])) {
            $text = $update['message']['text'];
            $command = explode(' ', $text)[0];
            if (isset($this->commands[$command])) {
                $this->commands[$command]->execute($update);
            }else{
                $this->commands[""]->execute($update);
            }
        }
    }

    public function sendMessage($chatId, $text) {
    $data = [
      'chat_id' => $chatId,
      'text' => $text
    ];
    $this->apiRequest($this->getApiUrl('sendMessage'), $data);
    }

    public function getApiUrl($method) {
        return 'https://api.telegram.org/bot'. $this->token. '/'. $method;
    }

    public function apiRequest($url, $data = null) {
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => is_array($data),
            CURLOPT_POSTFIELDS => is_array($data)? http_build_query($data) : null,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function run() {
        $offset = 0;
        while (true) {
            $url = $this->getApiUrl('getUpdates'). '?offset='. $offset;
            $response = json_decode($this->apiRequest($url), true);
            if (isset($response['result'][0]['update_id'])) {
                $update = $response['result'][0];
                $offset = $update['update_id'] + 1;
                $this->handle($update);
            }
        }
    }
}
