<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    /**
     * Get all products.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $products = Product::with(['category', 'grade'])
            ->when($request->category_id, function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })
            ->when($request->grade_id, function ($query) use ($request) {
                return $query->whereHas('grade', function ($q) use ($request) {
                    $q->where('grades.id', $request->grade_id);
                });
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
            ->paginate($request->per_page ?? 10);

        return $this->sendResponse($products, 'Products retrieved successfully');
    }

    /**
     * Get specific product.
     *
     * @param  Product  $product
     * @return JsonResponse
     */
    public function show(Product $product)
    {
        $product->load(['category', 'grade']);
        return $this->sendResponse($product, 'Product retrieved successfully');
    }

    /**
     * Search products.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        // Log incoming request data for debugging
        \Log::info('Product search request data:', [
            'all' => $request->all(),
            'query_params' => $request->query(),
            'query_input' => $request->input('query'),
        ]);

        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $products = Product::with(['category', 'grade'])
            ->where('name', 'like', '%' . $request->input('query') . '%')
            ->orWhere('description', 'like', '%' . $request->input('query') . '%')
            ->paginate($request->per_page ?? 10);

        return $this->sendResponse($products, 'Search results retrieved successfully');
    }

    /**
     * Create a new product.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Get the default category
            $defaultCategory = DB::table('categories')->first();
            if (!$defaultCategory) {
                throw new \Exception('No categories found in the database');
            }

            // Debug the incoming request
            \Log::info('Raw request data:', [
                'all' => $request->all(),
                'has_category_id' => $request->has('category_id'),
                'category_id' => $request->input('category_id'),
                'default_category_id' => $defaultCategory->id
            ]);

            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow image files up to 2MB
                'description' => 'required|string',
                'category_id' => 'nullable|exists:categories,id',
                'grade_id' => 'nullable|exists:grades,id',
                'stock' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return $this->sendValidationError($validator->errors());
            }

            // Handle file upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $filename);
                $photoPath = 'uploads/products/' . $filename;
            } else {
                throw new \Exception('No photo file uploaded');
            }

            // Create product with explicit data
            $product = new Product();
            $product->code = $request->code;
            $product->name = $request->name;
            $product->model = $request->model;
            $product->price = $request->price;
            $product->photo = $photoPath;
            $product->description = $request->description;
            $product->category_id = $request->input('category_id', $defaultCategory->id);
            $product->grade_id = $request->grade_id;
            $product->stock = $request->stock ?? 0;
            $product->is_active = $request->is_active ?? true;
            
            \Log::info('Creating product with data:', $product->toArray());
            
            // Save the product and check if it was successful
            if (!$product->save()) {
                throw new \Exception('Failed to save product');
            }
            
            \Log::info('Product created successfully:', ['product' => $product->toArray()]);
            
            return $this->sendResponse($product, 'Product created successfully');
        } catch (\Exception $e) {
            \Log::error('Product creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return $this->sendError('Failed to save product: ' . $e->getMessage());
        }
    }
} 