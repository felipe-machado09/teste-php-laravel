<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessDocumentImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $doc;

    /**
     * Create a new job instance.
     *
     * @param array $doc
     * @return void
     */
    public function __construct(array $doc)
    {
        $this->doc = $doc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $category = DB::table('categories')
            ->where('name', $this->doc['categoria'])
            ->first();

        if (!empty($category)) {
            DB::table('documents')->insert([
                'category_id' => $category->id,
                'title' => $this->doc['titulo'],
                'contents' => $this->doc['conteúdo'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
