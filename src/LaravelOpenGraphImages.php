<?php

namespace Abordage\LaravelOpenGraphImages;

use Abordage\OpenGraphImages\OpenGraphImages;

/**
 * @see \Abordage\OpenGraphImages\OpenGraphImages
 */
class LaravelOpenGraphImages extends OpenGraphImages
{
    public function __construct()
    {
        $config = is_array(config('og-images')) ? config('og-images') : [];
        parent::__construct($config);
    }
}
