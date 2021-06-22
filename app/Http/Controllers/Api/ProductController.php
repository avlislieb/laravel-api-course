<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProductFormRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    private $product;
    private $totalPage = 10;
    private $path = 'products';

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $product = $this->product->getResults($request->all(), $this->totalPage);

        return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateProductFormRequest $request)
    {
        $data = $request->all();


        try {
            DB::beginTransaction();
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data['image'] = $this->saveImage($request);
                if (empty($data['image'])) {
                    response()->json(['error' => 'Fail_upload!'], 500);
                }
            }
            $product = $this->product->create($data);
            DB::commit();
            return response()->json($product, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error update product!'], $e->getCode());
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
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['error' => 'Not found!'], 404);
        }
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateProductFormRequest $request, $id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['error' => 'Not found!'], 404);
        }
        $data = $request->all();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $this->deleteImage($product);
            $data['image'] = $this->saveImage($request, $product, true);
            if (empty($data['image'])) {
                response()->json(['error' => 'Fail_upload!'], 500);
            }
        }
        try {
            DB::beginTransaction();

            $product->update($data);
            DB::commit();
            return response()->json($product);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error update product!'], $e->getCode());
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
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['error' => 'Not found!'], 404);
        }

        try {
            DB::beginTransaction();
            $this->deleteImage($product);
            $product->delete();
            DB::commit();
            return response()->json([], 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error delete product!'], $e->getCode());
        }
    }

    /**
     * save image
     *
     * @param  Request  $request
     * @return String
     */
    private function saveImage(Request $request)
    {

        $name = Str::kebab($request->name);
        $extension = $request->image->extension();
        $nameFile = "{$name}.{$extension}";
        $uploadImage = $request->image->storeAs($this->path, $nameFile);
        if (!$uploadImage) {
            return '';
        }
        return $nameFile;
    }

    /**
     * delete image
     *
     * @param  Product  $product
     * @return Void
     */
    private function deleteImage(Product $product)
    {
        Storage::delete("{$this->path}/{$product->image}");
    }

}
