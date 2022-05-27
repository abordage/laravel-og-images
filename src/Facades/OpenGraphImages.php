<?php

namespace Abordage\LaravelOpenGraphImages\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Abordage\LaravelOpenGraphImages\OpenGraphImages make(string $text, int $width = 1200, int $height = 630)
 * @method static \Abordage\LaravelOpenGraphImages\OpenGraphImages makeTwitter(string $text)
 * @method static \Abordage\LaravelOpenGraphImages\OpenGraphImages makeVk(string $text)
 * @method string|null get()
 * @method bool save(string $path)
 *
 * @see \Abordage\LaravelOpenGraphImages\OpenGraphImages
 */
class OpenGraphImages extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-og-images';
    }
}
