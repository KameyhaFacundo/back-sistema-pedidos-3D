<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SSEController extends Controller
{
    public function stream(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        $lastCheck = now()->subSeconds(5);

        $callback = function () use (&$lastCheck) {
            $pedidos = Pedido::with(['items.plato', 'mesa'])
                ->where('updated_at', '>', $lastCheck)
                ->orderBy('updated_at', 'desc')
                ->get();

            if ($pedidos->isNotEmpty()) {
                echo "data: " . json_encode(['pedidos' => $pedidos]) . "\n\n";
                ob_flush();
                flush();
            }

            $lastCheck = now();

            sleep(3);
            return true;
        };

        while (true) {
            if (connection_aborted()) break;
            if (!$callback()) break;
        }

        return $response;
    }
}
