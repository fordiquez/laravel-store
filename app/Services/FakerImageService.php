<?php

namespace App\Services;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FakerImageService extends Base
{
    public function fakeImage(string $dir = 'images', string $name = ''): string
    {
        $width = $dir === 'users' ? 500 : 994;
        $height = $dir === 'users' ? 500 : 1280;
        $filename = !empty($name) ? strtolower($name) : Str::random(8);
        if ($dir === 'flags') {
            $format = 'svg';
            $imageURL = "https://raw.githubusercontent.com/MohmmedAshraf/blade-flags/main/resources/svg/country-$filename.svg";
        } else {
            $format = 'jpg';
            $imageURL = "https://loremflickr.com/$width/$height";
        }
        $path = "$dir/$filename.$format";

        Storage::disk('public')->put($path, file_get_contents($imageURL));

        return $path;
    }
}
