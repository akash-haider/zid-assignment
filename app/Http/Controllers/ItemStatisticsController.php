<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

    private function getWebsiteWithHighestPrice(): string
    {
        $response = Item::orderByDesc('price')
            ->pluck('url')->first();

        return $response;
    }

    private function getWebsiteWithHighestTotalPriceItems(): string
    {
        $response = Item::select([
            DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(url, '/', 3), '://', -1) AS domain"),
            DB::raw("SUM(price) AS total_price")
        ])
            ->groupBy('domain')
            ->orderByDesc('total_price')
            ->pluck('domain')->first();

        return $response;
    }

    private function getTotalPriceOfItemsAddedCurrentMonth(): string
    {
        $response = Item::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('price');

        return number_format($response, 2);
    }

    public function index(): JsonResponse
    {
        $statistics = [
            'total_items' => $this->getTotalItemsCount(),
            'average_price' => $this->getAveragePriceItem(),
            'website_highest_price' => $this->getWebsiteWithHighestPrice(),
            'website_highest_total_price_items' => $this->getWebsiteWithHighestTotalPriceItems(),
            'total_price_current_month' => $this->getTotalPriceOfItemsAddedCurrentMonth(),
        ];

        return response()->json(['statistics' => $statistics]);
    }

    public function showSpecificStat($option): JsonResponse
    {
        $result = null;

        switch ($option) {
            case 'total_items':
                $result = $this->getTotalItemsCount();
                break;
            case 'average_price':
                $result = $this->getAveragePriceItem();
                break;
            case 'website_highest_price':
                $result = $this->getWebsiteWithHighestPrice();
                break;
            case 'website_highest_total_price_items':
                $result = $this->getWebsiteWithHighestTotalPriceItems();
                break;
            case 'total_price_current_month':
                $result = $this->getTotalPriceOfItemsAddedCurrentMonth();
                break;
            default:
                return response()->json(['message' => 'Invalid parameter'], 404);
        }

        return response()->json(['statistics' => [$option => $result]]);
    }
}
