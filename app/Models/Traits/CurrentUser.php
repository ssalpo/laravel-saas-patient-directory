<?php

namespace App\Models\Traits;

trait CurrentUser
{
    public function scopeMy($q)
    {
        return $q->whereUserId(auth()->id());
    }
}
