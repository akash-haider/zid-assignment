<?php

namespace App\Console\Commands;

use App\Http\Controllers\ItemStatisticsController;
use Illuminate\Console\Command;

class DisplayItemStatistics extends Command
{
    protected $signature = 'display:statistics {param? : specific parameter (total_items | average_price | website_highest_price | website_highest_total_price_items | total_price_current_month) to display statistics}';

    protected $description = 'Display statistics';

    private $itemStatisticsController;

    public function __construct(ItemStatisticsController $itemStatisticsController)
    {
        parent::__construct();
        $this->itemStatisticsController = $itemStatisticsController;
    }

    public function handle()
    {
        $param = $this->argument('param');

        if ($param) {
            $this->displaySpecificStat($param);
        } else {
            $this->displayAllStatistics();
        }
    }

    private function getStatistics(): array
    {
        $response = $this->itemStatisticsController->index();
        return $this->parseResponse($response);
    }

    private function getSpecificStat($param): array
    {
        $response = $this->itemStatisticsController->showSpecificStat($param);
        return $this->parseResponse($response);
    }

    private function parseResponse($response): array
    {
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return json_decode($response->getContent(), true)['statistics'] ?? [];
        }

        return [];
    }

    private function displaySpecificStat($param)
    {
        $statistics = $this->getSpecificStat($param);
        $value = $statistics[$param] ?? null;

        if ($value !== null) {
            $this->info($param . ': ' . $value);
        } else {
            $this->error('Invalid key provided.');
        }
    }

    private function displayAllStatistics()
    {
        $statistics = $this->getStatistics();
        $data = [];

        foreach ($statistics as $key => $value) {
            $data[] = [$key, $value];
        }

        $this->table(['Statistic', 'Value'], $data);
    }

}
