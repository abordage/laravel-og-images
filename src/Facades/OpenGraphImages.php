<?php

namespace Abordage\LaravelOpenGraphImages\Facades;

use Abordage\LaravelOpenGraphImages\LaravelOpenGraphImages;
use Illuminate\Support\Facades\Facade;

/**
 * @method static LaravelOpenGraphImages make(string $text, int $width = 1200, int $height = 630)
 * @method static LaravelOpenGraphImages makeTwitter(string $text)
 * @method static LaravelOpenGraphImages makeVk(string $text)
 * @method string|null get()
 * @method bool save(string $path)
 *
 * @see \Abordage\LaravelOpenGraphImages\LaravelOpenGraphImages
 */
class OpenGraphImages extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-og-images';
    }
}
