<?php
namespace App\Http\Controllers\Shop; use App\Category; use App\Product; use App\Library\Response; use Carbon\Carbon; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class Coupon extends Controller { function info(Request $sp147552) { $sp43c1c1 = (int) $sp147552->post('category_id', -1); $sp52cac1 = (int) $sp147552->post('product_id', -1); $spb940a6 = $sp147552->post('coupon'); if (!$spb940a6) { return Response::fail(trans('shop.coupon.required')); } if ($sp43c1c1 > 0) { $sp62ae3e = Category::findOrFail($sp43c1c1); $spa27b73 = $sp62ae3e->user_id; } elseif ($sp52cac1 > 0) { $sp4aad76 = Product::findOrFail($sp52cac1); $spa27b73 = $sp4aad76->user_id; } else { return Response::fail(trans('shop.please_select_category_or_product')); } $sp76025f = \App\Coupon::where('user_id', $spa27b73)->where('coupon', $spb940a6)->where('expire_at', '>', Carbon::now())->whereRaw('`count_used`<`count_all`')->get(); foreach ($sp76025f as $spb940a6) { if ($spb940a6->category_id === -1 || $spb940a6->category_id === $sp43c1c1 && ($spb940a6->product_id === -1 || $spb940a6->product_id === $sp52cac1)) { $spb940a6->setVisible(array('discount_type', 'discount_val')); return Response::success($spb940a6); } } return Response::fail(trans('shop.coupon.invalid')); } }