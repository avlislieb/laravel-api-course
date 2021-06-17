<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategoryFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index(Request $request)
    {
        $categories = $this->category->getResults($request->get('name', ''));

        return response()->json($categories);
    }

    public function store(StoreUpdateCategoryFormRequest $request)
    {
        $category = $this->category->create($request->all());

        return response()->json($category, 201);
    }

    public function update(StoreUpdateCategoryFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $category = $this->category->find($id);
            if (!$category) {
                return response()->json(['message' => "Category not found"], 404);
            }
            $category->update($request->all());
            DB::commit();
            return response()->json($category);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Error"], $e->getCode());
        }
    }

    public function destroy($id)
    {
        $category = $this->category->find($id);
        if (!$category) {
            return response()->json(['message' => "Category not found"], 404);
        }
        $category->delete();
        return response()->json(['success' => true], 204);
    }

    public function show($id)
    {
        $category = $this->category->find($id);
        if (!$category) {
            return response()->json(['message' => "Category not found"], 404);
        }
        return response()->json($category);
    }
}
