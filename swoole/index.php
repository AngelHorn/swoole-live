<?php
//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 9502);
$ws->set([
    'buffer_output_size' => 32 * 1024 *1024, //必须为数字
]);
//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
$stream = file_get_contents("./accueil.mp4");
//    $fuck= var_dump($request->fd, $request->get, $request->server);
    $ws->push($request->fd, $stream,0x2);
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    $ws->push($frame->fd, "server: {$frame->data}",0x2);
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();
