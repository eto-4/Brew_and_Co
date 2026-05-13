<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateWeeklyPopular implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $populars = Product::withCount(['orderLines' => function ($query) {
            $query->whereHas('order', function ($q) {
                $q->where('created_at', '>=', now()->subWeek());
            });
        }])
        ->orderBy('order_lines_count', 'desc')
        ->take(3)
        ->pluck('id');

        cache()->put('productes_populars', $populars, now()->addWeek());
    }
}