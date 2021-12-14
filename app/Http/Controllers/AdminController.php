<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\CTBill;
use App\Models\Bill;
use Carbon\Carbon;
use App\Http\Requests\Admin\NewProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function sanpham(Request $request)
    {
        $limit = $request->get('limit', 5);
        $offset = $request->get('offset', 0);

        $product = new Product;

        $spCount = $product->get()->count();
        $spList = $product->orderBy('id', 'desc')->skip($offset)->take($limit)->get();
        return response()->json([
            'success' => true,
            'data' => $spList,
            'meta' => [
                'total' => $spCount
            ]
        ], 200);
    }
    

    public function ctProduct($id)
    {
        $sp = Product::where('id', $id)->first();
        return response()->json([
            'success' => true,
            'data' => $sp
        ]);
    }

    public function newProduct(NewProductRequest $request)
	{
		$createPorduct = new Product;
		$createPorduct->id_dm = $request->category;
		$createPorduct->tensp = $request->title;
		$createPorduct->chitiet = $request->content;
        $createPorduct->id_th = $request->brand;
        $createPorduct->gia = $request->price;
        $createPorduct->so_luong = $request->quantity;
        $createPorduct->khadung = $request->availability;

		if ($request->hasfile('image')) {
			$imageName = time() . '.' . $request->file('image')->extension();
			Storage::disk('imagesUpload')->put($imageName, file_get_contents($request->file('image')));
			$createPorduct->hinh = $imageName;
		}

		$createPorduct->save();
		$lastId = $createPorduct->id;

		$product = Product::where('id', $lastId)->firstOrFail();
		return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
	}

    public function updateProduct(UpdateProductRequest $request, $id)
	{
		$updatePorduct = Product::where('id', $id)->firstOrFail();

		$updatePorduct->id_dm = $request->category;
		$updatePorduct->tensp = $request->title;
		$updatePorduct->chitiet = $request->content;
        $updatePorduct->id_th = $request->brand;
        $updatePorduct->gia = $request->price;
        $updatePorduct->so_luong = $request->quantity;
        $updatePorduct->khadung = $request->availability;

		if ($request->hasfile('image')) {
            $oldImage = $updatePorduct->hinh;
			if (Storage::exists($oldImage)) {
				Storage::delete($oldImage);
			}
			$imageName = time() . '.' . $request->file('image')->extension();
			Storage::disk('imagesUpload')->put($imageName, file_get_contents($request->file('image')));
			$updatePorduct->hinh = $imageName;
		}

		$updatePorduct->save();
		$lastId = $updatePorduct->id;

		$product = Product::where('id', $lastId)->firstOrFail();
		return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
	}

    public function category() {
        $dm = Category::get();

        return response()->json([
            'success' => true,
            'data' => $dm
        ]);
    }

    public function brand() {
        $th = Brand::get();

        return response()->json([
            'success' => true,
            'data' => $th
        ]);
    }

    public function login(Request $request)
    {
        $credentials = request(['user_name', 'password']);
        if (!auth()->attempt($credentials) || !auth()->user()->is_admin)
            return response()->json([
                'success' => false,
                'errors' => [
                    "user" => "User name or password does not exists"
                ]
            ], 200);
        $tokenResult = auth()->user()->createToken('Personal Access Token');
        return response()->json([
            'success' => true,
            'data' => [
                'id' => auth()->user()->id,
                'user_name' => auth()->user()->user_name,
                'ho_ten' => auth()->user()->ho_ten,
                'sdt' => auth()->user()->sdt,
                'dia_chi' => auth()->user()->dia_chi,
                'email' => auth()->user()->email,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]
        ], 200);
    }

    public function destroy($id)
	{
		$deleteProduct = Product::where('id', $id)->firstOrFail();
		$deleteProduct->delete();
        return response()->json([
            'success' => true,
            'data' => $deleteProduct,
        ], 200);
	}
    public function destroy_1($id)
	{
		$deleteProduct = User::where('id', $id)->firstOrFail();
		$deleteProduct->delete();
        return response()->json([
            'success' => true,
            'data' => $deleteProduct,
        ], 200);
	}
    public function customer (Request $request)
    {
        $limit = $request->get('limit', 5);
        $offset = $request->get('offset', 0);

        $customer = new User;

        $khCount = $customer->get()->count();
        $khList = $customer->orderBy('id', 'desc')->skip($offset)->take($limit)->get();
        return response()->json([
            'success' => true,
            'data' => $khList,
            'meta' => [
                'total' => $khCount
            ]
        ], 200);
    }
    public function bill (Request $request)
    {
        $limit = $request->get('limit', 5);
        $offset = $request->get('offset', 0);

        $ctbill = Bill::with('ctbill.product');

        $billCount = $ctbill->get()->count();
      
        return response()->json([
            'success' => true,
            'data' => $ctbill->get(),
            'meta' => [
                'total' => $billCount
            ]
        ], 200);
    }
       
}