<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        protected Product $repository,
    ) {
        $this->middleware('auth', ['only' => ['store']]);
    }

    public function index()
    {
        $products = $this->repository->paginate();
        return ProductResource::collection($products);
    }

    public function store(StoreUpdateProductRequest $request)
    {
        $data = $request->validated();
        $product = $this->repository->create($data);

        return new ProductResource($product);
    } 

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
