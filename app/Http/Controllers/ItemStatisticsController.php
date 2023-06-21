<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemStatisticsController extends Controller
{
    private function getTotalItemsCount(): int
    {
        return Item::count();
    }

    private function getAveragePriceItem(): string
    {
        $average = Item::avg('price');

        return number_format($average, 2);
    }

    private function getWebsiteWithHighestTotalPrice(): string
    {
        $response = Item::where('price', function ($query) {
            $query->selectRaw('MAX(price)')->from('items');
        })->pluck('url')->first();

        return $response;
    }

    private function getTotalPriceOfItemsAddedCurrentMonth(): string
    {
        $currentMonth = now()->format('Y-m');

        $response = Item::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = '$currentMonth'")
            ->sum('price');

        return number_format($response, 2);
    }

    public function index(): JsonResponse
    {
        $statistics = [
            'total_items' => $this->getTotalItemsCount(),
            'average_price' => $this->getAveragePriceItem(),
            'website_highest_price' => $this->getWebsiteWithHighestTotalPrice(),
            'total_price_current_month' => $this->getTotalPriceOfItemsAddedCurrentMonth(),
        ];

        return response()->json(['statistics' => $statistics]);
    }

    public function showSpecificStat(Request $request, $option): JsonResponse
    {
        if ($option === 'total_items') {
            $result = $this->getTotalItemsCount();
        } elseif ($option === 'average_price') {
            $result = $this->getAveragePriceItem();
        } elseif ($option === 'website_highest_price') {
            $result = $this->getWebsiteWithHighestTotalPrice();
        } elseif ($option === 'total_price_current_month') {
            $result = $this->getTotalPriceOfItemsAddedCurrentMonth();
        }else{
            return response()->json(['message' => 'invalid parameter'], 404);
        }

        return response()->json(['statistics' => [$option => $result]]);
    }
}
