<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ResizePatientPhotos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private array $photos)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->photos as $photo) {
            $img = Image::make(public_path('/storage/'.$photo->url));

            if ($img->getWidth() > 1000) {
                $img->resize(
                    1000,
                    null,
                    fn ($const) => $const->aspectRatio()
                )->save(public_path('/storage/thumb/'.$photo->url));

                $photo->update(['has_thumb' => true]);
            }
        }
    }
}
