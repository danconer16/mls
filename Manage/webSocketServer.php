<?php

define("ROOT","D:/wamp64/www/mls/");
define("BASE",dirname(__FILE__));
define("DS",DIRECTORY_SEPARATOR);
define("ACCESS", 1);

require_once(ROOT."/Includes/definitions.php");
require_once(ROOT."/Includes/define.php");
require_once(ROOT."/Includes/functions.php");
require_once(ROOT."/Vendor/autoload.php");
require_once(ROOT."/Vendor/pecee/simple-router/helpers.php");

function autoloader($class) {
	if (file_exists($file = BASE.'/'.str_replace('\\', '/', $class).'.php')) {
		require_once($file);
	}else if (file_exists($file = ROOT.'/'.str_replace('\\', '/', $class).'.php')) {
		require_once($file);
	}
}

spl_autoload_register('autoloader');

echo "# MLS Messaging Echo server!\n";

// Server options specified or default
$options = array_merge([
    'port'  => 8980,
    'timeout'   => 20,
], getopt('', ['port:', 'ssl', 'timeout:', 'framesize:', 'debug']));

// Initiate server.
try {
    $server = new \WebSocket\Server($options['port'], isset($options['ssl']));
    $server
        ->addMiddleware(new \WebSocket\Middleware\CloseHandler())
        ->addMiddleware(new \WebSocket\Middleware\PingResponder())
        ;

    // If debug mode and logger is available
    if (isset($options['debug']) && class_exists('WebSocket\Test\EchoLog')) {
        $server->setLogger(new \WebSocket\Test\EchoLog());
        echo "# Using logger\n";
    }
    if (isset($options['timeout'])) {
        $server->setTimeout($options['timeout']);
        echo "# Set timeout: {$options['timeout']}\n";
    }
    if (isset($options['framesize'])) {
        $server->setFrameSize($options['framesize']);
        echo "# Set frame size: {$options['framesize']}\n";
    }

    echo "# Listening on port {$server->getPort()}\n";
    $server->onConnect(function ($server, $connection, $handshake) {
        echo "> [{$connection->getRemoteName()}] Client connected {$handshake->getUri()}\n";
    })->onDisconnect(function ($server, $connection) {
        echo "> [{$connection->getRemoteName()}] Client disconnected\n";
    })->onText(function ($server, $connection, $message) {
        echo "> [{$connection->getRemoteName()}] Received [{$message->getOpcode()}] {$message->getContent()}\n";
        switch ($message->getContent()) {
            // Connection commands
            case '@close':
                echo "< [{$connection->getRemoteName()}] Sending Close\n";
                $connection->send(new \WebSocket\Message\Close());
                break;
            case '@ping':
                echo "< [{$connection->getRemoteName()}] Sending Ping\n";
                $connection->send(new \WebSocket\Message\Ping());
                break;
            case '@disconnect':
                echo "< [{$connection->getRemoteName()}] Disconnecting\n";
                $connection->disconnect();
                break;
            case '@info':
                $msg = "Connection info:\n";
                $msg .= "  - Local:       {$connection->getName()}\n";
                $msg .= "  - Remote:      {$connection->getRemoteName()}\n";
                $msg .= "  - Request:     {$connection->getHandshakeRequest()->getUri()}\n";
                $msg .= "  - Response:    {$connection->getHandshakeResponse()->getStatusCode()}\n";
                $msg .= "  - Connected:   " . json_encode($connection->isConnected()) . "\n";
                $msg .= "  - Readable:    " . json_encode($connection->isReadable()) . "\n";
                $msg .= "  - Writable:    " . json_encode($connection->isWritable()) . "\n";
                $msg .= "  - Timeout:     {$connection->getTimeout()}s\n";
                $msg .= "  - Frame size:  {$connection->getFrameSize()}b\n";
                echo "< [{$connection->getRemoteName()}] {$msg}";
                $server->send(new \WebSocket\Message\Text($msg));
                break;

            // Server commands
            case '@server-stop':
                echo "< [{$connection->getRemoteName()}] Stop server\n";
                $server->stop();
                break;
            case '@server-close':
                echo "< [{$connection->getRemoteName()}] Broadcast Close\n";
                $server->send(new \WebSocket\Message\Close());
                break;
            case '@server-ping':
                echo "< [{$connection->getRemoteName()}] Broadcast Ping\n";
                $server->send(new \WebSocket\Message\Ping());
                break;
            case '@server-disconnect':
                echo "< [{$connection->getRemoteName()}] Disconnecting server\n";
                $server->disconnect();
                break;
            case '@server-info':
                $msg = "Server info:\n";
                $msg .= "  - Running:     " . json_encode($server->isRunning()) . "\n";
                $msg .= "  - Connections: {$server->getConnectionCount()}\n";
                $msg .= "  - Port:        {$server->getPort()}\n";
                $msg .= "  - Scheme:      {$server->getScheme()}\n";
                $msg .= "  - Timeout:     {$server->getTimeout()}s\n";
                $msg .= "  - Frame size:  {$server->getFrameSize()}b\n";
                echo "< [{$connection->getRemoteName()}] {$msg}";
                $server->send(new \WebSocket\Message\Text($msg));
                break;

            // Echo received message
            default:
                $server->send($message); // Echo
                /* echo "< [{$connection->getRemoteName()}] Sent [{$message->getOpcode()}] {$message->getContent()}\n"; */
        }
    })->onBinary(function ($server, $connection, $message) {
        echo "> [{$connection->getRemoteName()}] Received [{$message->getOpcode()}]\n";
        $connection->send($message); // Echo
        /* echo "< [{$connection->getRemoteName()}] Sent [{$message->getOpcode()}] {$message->getContent()}\n"; */
    })->onPing(function ($server, $connection, $message) {
        echo "> [{$connection->getRemoteName()}] Received [{$message->getOpcode()}] {$message->getContent()}\n";
    })->onPong(function ($server, $connection, $message) {
        echo "> [{$connection->getRemoteName()}] Received [{$message->getOpcode()}] {$message->getContent()}\n";
    })->onClose(function ($server, $connection, $message) {
        echo "> [{$connection->getRemoteName()}] Received [{$message->getOpcode()}] "
            . "{$message->getCloseStatus()} {$message->getContent()}\n";
    })->onError(function ($server, $connection, $exception) {
        echo "> Error: {$exception->getMessage()}\n";
    })->start();
} catch (Throwable $e) {
    echo "# ERROR: {$e->getMessage()}\n";
}

ob_flush();
flush();