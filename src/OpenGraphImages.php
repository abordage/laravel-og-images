<?php

namespace Abordage\LaravelOpenGraphImages;

use Abordage\OpenGraphImages\OpenGraphImages as BaseOpenGraphImages;

class OpenGraphImages extends BaseOpenGraphImages
{
    public function __construct()
    {
        $config = is_array(config('og-images')) ? config('og-images') : [];
        parent::__construct($config);
    }
}
