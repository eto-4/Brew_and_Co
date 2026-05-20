<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job encarregat d'actualitzar els productes més populars de la setmana.
 *
 * Calcula els productes amb més aparicions en comandes recents
 * i desa el resultat en memòria cau per optimitzar consultes.
 */
class UpdateWeeklyPopular implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Executa el càlcul dels productes més populars de la setmana.
     *
     * Obtè els tres productes amb més línies de comanda associades
     * durant els últims set dies i els emmagatzema a la memòria cau.
     *
     * @return void
     */
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