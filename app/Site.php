<?php
/**
 * Created by PhpStorm.
 * User: quandm
 * Date: 4/17/2017
 * Time: 2:18 PM
 */

namespace App;


use DB;
use Illuminate\Support\Str;

class Site
{
    public static function getCategoryUrl($category)
    {
        return Str::slug($category->name).'-'.$category->id;
    }

    public static function getProductUrl($product)
    {
        return Str::slug($product->name).'-'.$product->id.'.html';
    }

    public static function headerCategory()
    {
        return Category::where('status', true)->get();
    }

    public static function latestReleases()
    {
        return Product::where('status', true)->latest('created_at')->limit(6)->get();
    }

    public static function price($price)
    {
        return number_format($price, null, null, '.');
    }

    public static function displayJson($ars)
    {
        $html = null;
        if (is_array($ars)) {
            foreach ($ars as $key => $ar) {
                $html .= '&nbsp;&nbsp;<b>'.$key.'</b>&nbsp;&nbsp;'.self::displayJson($ar).'<br/>';
            }
        } else {
            $html .= filter_var($ars, FILTER_VALIDATE_URL)? '<a href="'.$ars.'">Link</a>' : $ars;
        }

        return $html;
    }

    public static function enoughProductForOrder($order)
    {
        foreach ($order->orderItems as $item) {
            $countProduct = DB::table('stock_products')
                ->where('product_id', $item->product_id)
                ->where('in_stock', true)
                ->count();

            if ($countProduct < $item->quantity) {
                return false;
            }
        }
        return true;
    }
}