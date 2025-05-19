<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Services\InventoryService;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller {

	use ValidatesRequests;

	protected $inventoryService;
	protected $purchaseService;

	public function __construct(InventoryService $inventoryService, PurchaseService $purchaseService)
    {
        $this->middleware('auth:web')->except('list');
		$this->inventoryService = $inventoryService;
		$this->purchaseService = $purchaseService;
    }

	public function list(Request $request) {

		$query = Product::with('inventory')->select("products.*");

		$query->when($request->keywords, function($q) use ($request) {
			return $q->where("name", "like", "%" . $request->keywords . "%");
		});

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null) {
		if(!auth()->user()) return redirect('/');

		// Check if user is an employee or admin
		$user = Auth::user();
		if(!$user->isEmployee() && !$user->isAdmin() && 
           !$user->hasPermissionTo('edit_products') && 
           !$user->hasPermissionTo('add_products') && 
           !$user->hasPermissionTo('manage_products')) {
			return redirect()->route('products_list')
				->with('error', 'You do not have permission to edit products');
		}

		$product = $product??new Product();
		$inventory = $product->inventory ?? new ProductInventory();

		return view('products.edit', compact('product', 'inventory'));
	}

	public function save(Request $request, Product $product = null) {
		// Check if user is an employee or admin
		$user = Auth::user();
		if(!$user->isEmployee() && !$user->isAdmin() && 
           !$user->hasPermissionTo('edit_products') && 
           !$user->hasPermissionTo('add_products') && 
           !$user->hasPermissionTo('manage_products')) {
			return redirect()->route('products_list')
				->with('error', 'You do not have permission to edit products');
		}

		$this->validate($request, [
	        'code' => ['required', 'string', 'max:32'],
	        'name' => ['required', 'string', 'max:128'],
	        'model' => ['required', 'string', 'max:256'],
	        'description' => ['required', 'string', 'max:1024'],
	        'price' => ['required', 'numeric', 'min:0'],
			'quantity' => ['required', 'integer', 'min:0']
	    ]);

		try {
			DB::beginTransaction();

			$product = $product??new Product();
			$product->fill($request->except('quantity'));
			$product->save();

			// Update inventory
			$this->inventoryService->updateInventory($product, $request->quantity);

			DB::commit();

			return redirect()->route('products_list')->with('success', 'Product saved successfully');
		} catch (\Exception $e) {
			DB::rollBack();
			return redirect()->back()->withInput($request->all())
				->with('error', 'Failed to save product: ' . $e->getMessage());
		}
	}

	public function delete(Request $request, Product $product) {
		// Check if user is logged in
		if(!auth()->check()) {
			return redirect()->route('login');
		}
		
		// Check if user is an employee or admin
		$user = Auth::user();
		if(!$user->isEmployee() && !$user->isAdmin() && 
           !$user->hasPermissionTo('delete_products') &&
           !$user->hasPermissionTo('manage_products')) {
			return redirect()->route('products_list')
				->with('error', 'You do not have permission to delete products');
		}
		
		$product->delete();

		return redirect()->route('products_list')->with('success', 'Product deleted successfully');
	}

	public function purchase(Request $request, Product $product)
	{
		if (!auth()->check()) {
			return redirect()->route('login');
		}

		$user = Auth::user();

		// Check if user is a customer
		if (!$user->isCustomer()) {
			return redirect()->route('products_list')
				->with('error', 'Only customers can purchase products');
		}

		$this->validate($request, [
			'quantity' => ['required', 'integer', 'min:1']
		]);

		$result = $this->purchaseService->purchaseProduct($user, $product, $request->quantity);

		if ($result['success']) {
			return redirect()->route('products_list')
				->with('success', $result['message']);
		} else {
			return redirect()->route('products_list')
				->with('error', $result['message']);
		}
	}

	public function purchaseHistory()
	{
		if (!auth()->check()) {
			return redirect()->route('login');
		}

		$user = Auth::user();
		$purchases = $this->purchaseService->getUserPurchases($user);

		return view('products.purchase_history', compact('purchases'));
	}

	public function showProduct(Product $product)
	{
		return view('products.show', compact('product'));
	}

    /**
     * Toggle favorite status for a product.
     */
    public function favorite(Request $request, Product $product)
    {
        $user = Auth::user();

        // Check if user is a customer
        if (!$user->isCustomer()) {
            return redirect()->back()->with('error', 'Only customers can favorite products.');
        }

        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            // Already favorited, so unfavorite
            $user->favorites()->detach($product->id);
            $message = 'Product unfavorited.';
        } else {
            // Not favorited, so favorite
            $user->favorites()->attach($product->id);
            $message = 'Product favorited.';
        }

        // Redirect back to the product list or product show page
        return redirect()->back()->with('success', $message);
    }

    /**
     * Display a list of favorited products.
     */
    public function favoritesList()
    {
        $user = Auth::user();
        $favoriteProducts = $user->favorites()->with('inventory')->get();

        return view('products.favorites_list', compact('favoriteProducts'));
    }
} 