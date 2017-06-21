<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\PhotoAlbumController;
use App\Models\ProductCategory;
use App\Models\MerchantAccount;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductOfTheWeek;
use App\Models\HottestProduct;
use App\Models\ProductNotification;
use App\Models\ProductHype;
use App\Models\Post;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $photo_album;

    public function __construct(PhotoAlbumController $photo_album)
    {
        $this->photo_album = $photo_album;
        //$this->middleware('merchant');
    }

    public function index()
    {
        //
        
        return view('merchant.merchant');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $product_categories = ProductCategory::all();
        return view('merchant.add_product', compact('product_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request,[
            'product_name'=>'required|max:20',
            'description' => 'required|max:50'
            ]);

        if(!empty($request->file('file'))){
            $this->validate($request, [
                'file.*' => 'required|mimes:jpg,jpeg,png,gif'
            ], ['All files must be images (jpg, jpeg, png, gif)']);
        }

        //get or create merchant acount and inventory
        $merchant = MerchantAccount::firstOrCreate(['user_id' => auth()->user()->id]);
        $inventory = Inventory::firstOrCreate(['merchant_account_id' => $merchant->id]);
        
        // $product_category_id = $request->input('category');
        $product = new Product();
        $product->name = $request->input('product_name');
        $product->description = $request->input('description');
        $product->price = $request->input('product_price');
        $product->quantity = $request->input('quantity');
        $product->product_category_id = $request->input('category');
        
        if(!empty($request->file('file'))){
            $album = $this->photo_album->store($request);
            $product->photo_album_id = $album;
        }

        if ($product->promo_price) {
            $price = $product->promo_price;
        } else {
            $price = $product->price;
        }

        // product notification for followers
        $product_notification = ProductNotification::create([
                'message' => 'Notice: ' . $product->inventory->merchant->user->first_name . " now has " . $product->name . ' at ' . $price,
                'product_id'=> $product->id, 
                'description_id' => 1
            ]);
        $product_notification->users()->attach(auth()->user()->followers);
        $product_notification->save();

        $product->inventory()->associate($inventory);
        $product->save();

        return redirect()->route('merchant')->with('info', 'Product Added Sucessfully');        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->with('pictures')->first();
        return view('merchant.product_details', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id=null)
    {
        //
        $product_categories = ProductCategory::all();
        $product = Product::find($id);
        // dd($product);
        if($request->isMethod('Post')){
            $product->name = $request->input('product_name');
            $product->description = $request->input('decription');
            $product->price = $request->input('product_price');
            $product->quantity = $request->input('quantity');
            $product->product_category_id = $request->input('category');
            $product->save();
            return redirect()->route('merchant')->with('info', 'Product Updated Sucessfully ');
        }else{
            return view('merchant.edit_product', compact('product', 'product_categories'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // dd('hi');
        $product = Product::destroy($id);
        return back()->with('info', 'Product Deleted Sucessfully');
    }

    public function viewAllProduct(){
        //get or create merchant acount and inventory
        $merchant = MerchantAccount::firstOrCreate(['user_id' => auth()->user()->id]);
        $inventory = Inventory::firstOrCreate(['merchant_account_id' => $merchant->id]);
        $products = Product::where('inventory_id', $inventory->id)->get();
        $product_of_the_week = ProductOfTheWeek::where('merchant_account_id', $merchant->id)->first();
        
        //hottest deal button status
        $hot_prod  = HottestProduct::firstOrCreate(['merchant_account_id' => auth()->user()->merchant_account->id]);
        if($hot_prod->interval_time == null){
            $hot_prod->interval_time = Carbon::now()->subWeek(2);
        }
        $diff_in_days = Carbon::now()->diffInDays($hot_prod->interval_time);
        if(($hot_prod->slots <= 6) || ($diff_in_days >= 7)){
            $hottest = true;
        } else {
            $hottest = false;
        }
        // dd($product_of_the_week);
        if($product_of_the_week!=null){
            // dd('net');
            $current_time = date('Y-m-d');
            $product_of_the_week_updated = date('Y-m-d', strtotime($product_of_the_week->updated_at));
            $current_time = date('Y-m-d', strtotime($current_time.' - 7days'));
            // dd($current_time);
            $diff_in_days = $current_time >= $product_of_the_week_updated;
            // dd($diff_in_days);
            // dd($current_time - $current_time);
            return view('merchant.products', compact('products', 'product_of_the_week', 'diff_in_days', 'hottest'));
        }else {
            // dd('hello');
            return view('merchant.products', compact('products', 'product_of_the_week', 'hottest'));

        }
        // dd('hi');
        // dd($product_of_the_week);
        // dd($product_of_the_week_updated, $next_week_time);

        // return view('merchant.products', compact('products', 'product_of_the_week'));
    }
    public function product_of_the_week(Request $request, $id){
        // a user cannot tamper with the product of the week in any way
        if($request->isMethod('post')){

            $product = Product::find($id);

            $merchant_account = MerchantAccount::where('user_id', auth()->user()->id)->first();
            $merchant_account_id = $merchant_account->id;
            $data = ['product_id' => $product->id, 'merchant_account_id' => $merchant_account_id, 'updated_at' => Carbon::now()];
            $update = ProductOfTheWeek::updateOrCreate(['merchant_account_id'=> $merchant_account_id], $data);
            if ($product->promo_price) {
                $price = $product->promo_price;
            } else {
                $price = $product->price;
            }
            
            $product_notification = ProductNotification::create([
                    'message' => 'Notice: ' . $product->inventory->merchant->user->first_name . "'s product of the week is " . $product->name . ' at ' . $price,
                    'product_id'=> $product->id, 
                    'description_id' => 2
                ]);
            $product_notification->users()->attach(auth()->user()->followers);
            $product_notification->save();
            return back()->with('info', 'Product Of The Week Made');
        }else{
            return back();
        }
    }

    public function viewProductOfTheWeek($id){
        $product_of_the_week = ProductOfTheWeek::find($id);
        dd($product_of_the_week);
    }

    public function promo(Request $request, $id){
        $product = Product::find($id);
        if($request->isMethod('post')){

            $product->promo_price = $request->input('promo_price');
            $product->save();
            //notify all mmerchant followers
            //dd($product->inventory->merchant);
            $product_notification = ProductNotification::create([
                    'message' => 'Promo: ' . $product->inventory->merchant->user->first_name . ' now sells ' . $product->name . ' at ' . $product->promo_price,
                    'product_id'=> $product->id, 
                    'description_id' => 3
                ]);
            $product_notification->users()->attach(auth()->user()->followers);
            $product_notification->save();
            // dd($product->promo_price);
            return redirect()->route('allProduct')->with('info', 'Promo Sucessfully Added');
            
        }else{
            return view('merchant.make_promo', compact('product'));
        }
    }

    public function remove_promo($id){
        $product = Product::find($id);
        $product->promo_price = null;
        $product->save();
        $product_notification = ProductNotification::create([
                    'message' => 'Notice: ' . $product->inventory->merchant->user->first_name . "'s promo for " . $product->name . ' has ended!',
                    'product_id'=> $product->id, 
                    'description_id' => 4
                ]);
        $product_notification->users()->attach(auth()->user()->followers);
        $product_notification->save();
        return redirect()->route('allProduct')->with('info', 'Promo Sucessfully Remove');
    }

    public function whats_new(){
       $products = Product::orderBy('updated_at', 'desc')->paginate(5);

       return view('merchant.whats_new', compact('products'));

    }

    public function product_hype(Product $product){

           $hype = ProductHype::where(['product_id' => $product->id, 'user_id' => auth()->user()->id])->first();
            if ($hype) {
                return back()->with('info', 'Product already hyped by you!');
            } else {
                $created_hype = ProductHype::create(['product_id' => $product->id, 'user_id' => auth()->user()->id]);
                Post::create([
                    'user_id' => auth()->user()->id,
                    'title' => $product->name ,
                    'content' => $product->description,
                    'photo_album_id' => $product->photo_album_id
                ]);
            }       
        
        return back()->with('success', 'Product Hyped!');
    }
}
