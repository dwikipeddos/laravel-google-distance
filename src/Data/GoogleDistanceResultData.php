<?php

namespace Dwikipeddos\LaravelGoogleDistance\Data;

use Spatie\LaravelData\Data;

class GoogleDistanceResultData extends Data
{
    public string $distance_text;
    public float $distance_value;

    public ?string $duration_text = null;
    public ?float $duration_value = null;
}
