<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CTCart;
use App\Models\Bill;
use App\Models\CTBill;
use App\Models\User;
use Illuminate\Http\Request;

class ql_matongController extends Controller
{

    public function product()
    {
        $sp = Product::limit(8)->get();
        return response()->json([
            'success' => true,
            'data' => $sp
        ], 200);
    }
    public function ctproduct($id)
    {
        $sp = Product::where('id', $id)->first();
        return response()->json([
            'success' => true,
            'data' => $sp
        ]);
    }
    public function danhmuc()
    {
        $dm = Category::limit(5)->get();
        return response()->json([
            'success' => true,
            'data' => $dm
        ]);
    }
        public function sptheodm($id)
        {
            $dm = Product::where('id_dm', $id)->get();
            return response()->json([
                'success' => true,
                'data' => $dm
            ]);
        }
    public function thuonghieu()
    {
        $th = Brand::limit(5)->get();
        return response()->json([
            'success' => true,
            'data' => $th
        ]);
    }
    public function sptheoth($id)
    {
        $th = Product::where('id_th', $id)->get();
        return response()->json([
            'success' => true,
            'data' => $th
        ]);
    }
    public function addgiohang(Request $request)
    {
        $ktgh = Cart::where('id_kh', $request->user()->id)->get();

        if ($ktgh->count() < 1) {
            $payload = [
                'id_kh' => $request->user()->id,
            ];
            $gh = new Cart($payload);
            $gh->save();
            $idgh = $gh->id;
        } else {
            $idgh = $ktgh->first()->id;
        }

        $ktct = CTCart::where('id_sp', $request->id_sp)->where('id_gh', $idgh)->get();
        if ($ktct->count() > 0) {
            $slsp = $ktct->first()->so_luong + $request->so_luong;
            if ($slsp < 1) {
                $delete =  CTCart::where('id_sp', $request->id_sp)
                    ->where('id_gh', $idgh)
                    ->delete();
                if ($delete) {
                    $response = [
                        'success' => true,
                        'data' => [$delete, $idgh]
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'errorMessage' => 'error 500',
                    ];
                }
            } else {
                $giasp = $ktct->first()->gia + $request->so_luong * $request->gia;
                $updateCt = CTCart::where('id_sp', $request->id_sp)
                    ->where('id_gh', $idgh)
                    ->update(['so_luong' => $slsp, 'gia' => $giasp]);
                if ($updateCt) {
                    $response = [
                        'success' => true,
                        'data' => [$updateCt, $idgh]
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'errorMessage' => 'error 500',
                    ];
                }
            }
        } else {
            $slsp = $request->so_luong;
            $giasp = $request->so_luong * $request->gia;
            $ctgh = new CTCart;
            $ctgh->id_gh = $idgh;
            $ctgh->id_sp = $request->id_sp;
            $ctgh->so_luong = $slsp;
            $ctgh->gia = $giasp;
            $ctgh->save();
            if ($ctgh) {
                $response = [
                    'success' => true,
                    'data' => [$ctgh, $idgh]
                ];
            } else {
                $response = [
                    'success' => false,
                    'errorMessage' => 'error 500',
                ];
            }
        }
        return response()->json($response, 200);
    }
    public function danhsachcart(Request $request)
    {
        $list_cart = Cart::with('ctcart.product')->where('id_kh', $request->user()->id)->first();
        return response()->json([
            'success' => true,
            'data' => $list_cart
        ]);
    }
    public function xoacart(Request $request)
    {
        $ktgh = Cart::where('id_kh', $request->user()->id)->first();
        $ctgh = CTCart::where('id_gh', $ktgh->id)->where('id_sp', $request->id_sp)->delete();
        if ($ctgh) {
            $response = [
                'success' => true,
                'data' => $ctgh
            ];
        } else {
            $response = [
                'success' => false,
                'data' => 'error'
            ];
        }
        return response()->json($response, 200);
    }
    public function xoatatcagh(Request $request)
    {
        $ktgh = Cart::where('id_kh', $request->user()->id)->first();
        $ctgh = CTCart::where('id_gh', $ktgh->id)->delete();
        if ($ctgh) {
            $response = [
                'success' => true,
                'data' => $ctgh
            ];
        } else {
            $response = [
                'success' => false,
                'data' => 'error'
            ];
        }
        return response()->json($response, 200);
    }
    public function thanhtoan(Request $request)
    {

        $hoadon = new Bill;
        $hoadon->id_kh = 1;
        $hoadon->ho_ten = $request->ho_ten;
        $hoadon->email = $request->email;
        $hoadon->sdt = $request->sdt;
        $hoadon->dia_chi = $request->dia_chi;
        $hoadon->ngay_dat = $request->ngay_dat;
        $hoadon->ngay_giao = $request->ngay_giao;
        $hoadon->da_duyet = 0;
        $hoadon->da_thanh_toan = 0;
        $hoadon->da_giao_hang = 0;
        $hoadon->phuong_thuc_thanh_toan = 0;
        $hoadon->phi_van_chuyen = $request->phi_van_chuyen;
        $hoadon->Ma_buudien = $request->Ma_buudien;
        $hoadon->save();
        $idhd = $hoadon->id;
        foreach ($request->gio_hang as $key => $giohang) {
            $cthoadon = new CTBill();
            $cthoadon->id_hd = $idhd;
            $cthoadon->id_sp = $giohang['product']['id'];
            $cthoadon->gia = $giohang['gia'];
            $cthoadon->so_luong = $giohang['so_luong'];
            $cthoadon->save();
        }
        $ktgh = Cart::where('id_kh', 1)->first();
        CTCart::where('id_gh', $ktgh->id)->delete();
        $response = [
            'success' => true,
            'data' => 'thanhcong'
        ];
        return response()->json($response, 200);
    }
    public function thongtin(Request $request)
    {
        $ktkh = User::select('id', 'ho_ten', 'user_name', 'email', 'sdt', 'dia_chi')->where('id', $request->user()->id)->first();
        if ($ktkh) {
            $response = [
                'success' => true,
                'data' => $ktkh
            ];
        } else {
            $response = [
                'success' => false,
                'data' => 'error'
            ];
        }
        return response()->json($response, 200);
    }
}