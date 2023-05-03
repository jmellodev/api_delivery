<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function __construct(
        protected Cart $repository,
    ) {
        // $this->middleware('auth', ['only' => ['store', 'show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = new Cart();
        $cart->create($request->all());

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Ítem adicionado ao carrinho'
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cart = Cart::with('item:id,name,description')
            ->whereUserId($id)
            ->latest()
            ->get();

        if ($cart->isEmpty())
            return response()->json(['message' => 'Não há itens no carrinho'], 404);

        return response()->json(['items' => $cart]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        // Verifica se o usuário tem permissão para atualizar o carrinho
        if ($cart->user_id != $request->user_id) {
            return response()->json(['message' => 'Você não tem permissão para atualizar este carrinho'], 403);
        }
        // Preenche os atributos do carrinho com os dados da requisição
        $cart->fill($request->only(['user_id', 'quantity', 'amount']));

        $cart->update();
        // Salva as alterações no banco de dados
        if ($cart->wasChanged()) {
            return response()->json(['message' => 'Carrinho atualizado com sucesso!']);
        } else {
            return response()->json(['message' => 'Houve algum erro ao tentar atualizar o carrinho'], 500);
        }

        /* $cart->user_id = $request->user_id;
        $cart->quantity = $request->quantity;
        $cart->amount = $request->amount;
        $cart->update();

        if ($cart)
            return response()->json(['success' => 'Atualizado com sucesso!']);

        return response()->json(['error' => 'Houve algum erro ao tentar atualizar!']); */
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->options()->detach();
        $cart->delete();
        // dd($cart);

        if ($cart) {
            return response()->json(['success' => 'Excluído com sucesso']);
        }
        return response()->json(['error' => 'Carrinho não existe']);
    }
}
