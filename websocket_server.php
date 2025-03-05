<?php
require 'vendor/autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;

class BidWebSocket implements MessageComponentInterface {
    protected $clients;
    protected $subscriptions = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        if ($data['type'] === 'subscribe') {
            $this->subscriptions[$from->resourceId] = $data['lot_id'];
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->subscriptions[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    public function broadcastBidUpdate($lotId, $data) {
        foreach ($this->clients as $client) {
            if (isset($this->subscriptions[$client->resourceId]) && 
                $this->subscriptions[$client->resourceId] === $lotId) {
                $client->send(json_encode($data));
            }
        }
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new BidWebSocket()
        )
    ),
    8080
);

$server->run();