<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Exception;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PixController extends Controller
{
    public function __construct(
        private Gerencianet $gerencianet
    ) {
    }

    public function createCharge(Request $request)
    {
        $cart = Cart::whereUserId($request->user_id)->with('item')->get();

        $retorno = array();

        foreach ($cart as $items) {
            $retorno[] = [
                'nome' => $items->item->name, // Nome do campo string (Nome) ≤ 50 characters
                'valor' => Str::limit($items->item->description, 190) // Dados do campo string (Valor) ≤ 200 characters
            ];
        }

        $body = [
            "calendario" => [
                "expiracao" => 3600, // Vencimento em 60min 3600
            ],
            "devedor" => [
                "cpf" => $request->cpf,
                "nome" => $request->name
            ],
            "valor" => [
                "original" => (string) $request->valor, // int 1000 = 10.00 - 10000 = 100.00
            ],
            "chave" => config('services.gerencianet.defaultkeypix'), // Chave pix da conta Gerencianet do recebedor
            "infoAdicionais" => $retorno
        ];

        try {
            $api = $this->gerencianet;
            $pix = $api->pixCreateImmediateCharge([], $body);

            if (!isset($pix['txid'])) {
                throw new Exception(('Erro ao inicializar transação. Tente novamente'));
            }

            $params = [
                'id' => $pix['loc']['id'],
            ];
            // Gera QRCode
            $qrcode = $api->pixGenerateQRCode($params);

            $return = [
                "code" => 200,
                "pix" => $pix,
                "qrcode" => $qrcode
            ];

            return response()->json($return);
        } catch (GerencianetException $e) {
            // Trate os erros da Gerencianet
            return response()->json([
                'code' =>    $e->code,
                'error' => $e->error,
                'description' => $e->errorDescription,
            ]);
        }
    }

    public function getCharge(Request $request, $id)
    {
        try {
            // Consulte a cobrança na Gerencianet
            $charge = $this->gerencianet->detailCharge(['id' => $id]);

            // Retorne os dados da cobrança na resposta
            return response()->json($charge['data']);
        } catch (GerencianetException $e) {
            // Trate os erros da Gerencianet
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function webHook(Request $request)
    {
        $notification = $request->input('notification');

        try {
            // Consulte a notificação na Gerencianet
            $details = $this->gerencianet->getNotification(['token' => $notification]);

            // Atualize o status do pedido de acordo com a notificação
            $status = $details['data']['status']['current'];
            $charge_id = $details['data']['identifiers']['charge_id'];
            // atualiza o status do pedido no seu sistema
            Log::info("Status: $status 'ID: '$charge_id");
        } catch (GerencianetException $e) {
            // Trate os erros da Gerencianet
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Retorne uma resposta de sucesso para a Gerencianet
        return response()->json(['message' => 'OK']);
    }
}
