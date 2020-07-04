<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Events\UserActionsInProducts;
use Illuminate\Http\Request;
Use App\Product;
use Carbon\Carbon;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = Product::all();

            $actionMessage = "Products are listed for the user: ".\Auth::user()->name;

            event(new UserActionsInProducts($actionMessage));

            return response()->json(['products' => $products], 201);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        try {
            $product = Product::create($request->all());

            $actionMessage = "Product created_at: ($product->created_at)[$product->id, $product->name] for the user: ".\Auth::user()->name;

            event(new UserActionsInProducts($actionMessage));

            return response()->json(['product' => $product], 201);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * List the specified resource by filtering.
     *
     * The collection returned with group(This is a orderBy with params) name(if search->name exists),
     * description(if search->description exists) and
     * category(if search->category exists). That's querys are
     * allocated in scopes of products model
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
            if($request->header('query') !== null) {
                $search = json_decode($request->header('query'));

                $result = Product::search($search)->group($search)->get();

                $actionMessage = "Products searched with filters: [".json_encode($search)."] for the user: ".\Auth::user()->name;

                event(new UserActionsInProducts($actionMessage));

                return response()->json(['filtered_products' => $result], 201);
            } else {
                return response()->json(['message' => 'Header query with search params not found'], 404);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $fieldsWillUpdate = $request->all();

            $actionMessage = "Product is updated in (".Carbon::now()."): [old values: ".json_encode($product)."][new values: ".json_encode($fieldsWillUpdate)."] for the user: ".\Auth::user()->name;

            foreach ($fieldsWillUpdate as $key => $field)
            {
                $product->{$key} = $field;
            }

            $product->save();

            event(new UserActionsInProducts($actionMessage));

            return response()->json(['product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product could not be found with the passed ID', 'stackTrace' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $productDeleted = Product::findOrFail($id);

            $deleted = $productDeleted->delete();

            $actionMessage = "Product is deleted in (".Carbon::now()."): [Object: ".json_encode($productDeleted)."] for the user: ".\Auth::user()->name;

            return response()->json(['deleted' => $deleted], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product could not be found with the passed ID', 'stackTrace' => $e->getMessage()], 500);
        }
    }
}
