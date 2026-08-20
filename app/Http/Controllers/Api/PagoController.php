<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagoController extends Controller
{
    public function preference(Request $request)
    {
        $validated = $request->validate([
            'pedido_id' => 'required|integer',
            'token' => 'required|string|max:80',
            'monto' => 'required|numeric|min:0',
            'return_url' => 'required|url',
        ]);

        $pedido = Pedido::with('empresa')->find($validated['pedido_id']);

        if (!$pedido || !$pedido->token || !hash_equals($pedido->token, $validated['token'])) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $accessToken = config('services.mercadopago.access_token');
        if (!$accessToken) {
            return response()->json(['message' => 'Pago online no configurado'], 503);
        }

        $empresa = $pedido->empresa;
        $monto = round((float) $validated['monto'], 2);

        $response = Http::withToken($accessToken)->post('https://api.mercadopago.com/checkout/preferences', [
            'items' => [[
                'title' => "Pedido #{$pedido->id} — {$empresa->nombre}",
                'quantity' => 1,
                'unit_price' => $monto,
                'currency_id' => 'ARS',
            ]],
            'external_reference' => (string) $pedido->id,
            'auto_return' => 'approved',
            'back_urls' => [
                'success' => $validated['return_url'],
                'pending' => $validated['return_url'],
                'failure' => $validated['return_url'],
            ],
            'notification_url' => url('/api/pagos/mp/webhook'),
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'No se pudo iniciar el pago (MercadoPago respondió ' . $response->status() . ')',
            ], 502);
        }

        $data = $response->json();

        return response()->json([
            'init_point' => $data['init_point'] ?? null,
        ]);
    }

    public function webhook(Request $request)
    {
        $type = $request->input('type');
        $paymentId = $request->input('data.id');

        if ($type !== 'payment' || !$paymentId) {
            return response()->json(['message' => 'ignored'], 200);
        }

        $accessToken = config('services.mercadopago.access_token');
        if (!$accessToken) {
            return response()->json(['message' => 'no config'], 503);
        }

        $payment = Http::withToken($accessToken)
            ->get("https://api.mercadopago.com/v1/payments/{$paymentId}")
            ->json();

        if (($payment['status'] ?? '') === 'approved') {
            $pedido = Pedido::find($payment['external_reference'] ?? null);
            if ($pedido && $pedido->estado_pago !== 'pagado') {
                $pedido->update([
                    'estado_pago' => 'pagado',
                    'medio_pago' => 'mercadopago',
                ]);
            }
        }

        return response()->json(['message' => 'ok']);
    }
}