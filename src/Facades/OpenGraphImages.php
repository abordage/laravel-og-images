<?php

namespace Abordage\LaravelOpenGraphImages\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Abordage\LaravelOpenGraphImages\OpenGraphImages make(string $text, string $preset = 'opengraph')
 * @method static \Abordage\LaravelOpenGraphImages\OpenGraphImages makeCustom(string $text, int $width, int $height)
 * @method string|null get()
 * @method bool save(string $path)
 * @method array getImageSizes()
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
