# Open Graph Image Generator for Laravel

Create Open Graph images (og:image, twitter:image, vk:image) for each (or some) site pages.

<p style="text-align: center;" align="center">
<a href="https://github.com/abordage/laravel-og-images/blob/master/docs/examples.md" title="Open Graph Images Generator">
    <img alt="Open Graph Images Generator" src="https://github.com/abordage/laravel-og-images/blob/master/docs/images/default-og-image-830x435.png">
</a>
</p>

<p style="text-align: center;" align="center">

<a href="https://packagist.org/packages/abordage/laravel-og-images" title="Packagist version">
    <img alt="Packagist Version" src="https://img.shields.io/packagist/v/abordage/laravel-og-images">
</a>

<a href="https://github.com/abordage/laravel-og-images/actions/workflows/tests.yml" title="GitHub Tests Status">
    <img alt="GitHub Tests Status" src="https://img.shields.io/github/workflow/status/abordage/laravel-og-images/Tests?label=tests">
</a>

<a href="https://github.com/abordage/laravel-og-images/actions/workflows/php-cs-fixer.yml" title="GitHub Code Style Status">
    <img alt="GitHub Code Style Status" src="https://img.shields.io/github/workflow/status/abordage/laravel-og-images/PHP%20CS%20Fixer?label=code%20style">
</a>

<a href="https://www.php.net/" title="PHP version">
    <img alt="Packagist PHP Version Support" src="https://img.shields.io/packagist/php-v/abordage/laravel-og-images">
</a>

<a href="https://github.com/abordage/laravel-og-images/blob/master/LICENSE.md" title="License">
    <img alt="License" src="https://img.shields.io/github/license/abordage/laravel-og-images">
</a>

</p>

Use page title to create an eye-catching page preview when users share the link on social networks or instant
messengers. [Learn more](https://ogp.me) about Open Graph.

## Features:

- Image generation with your text and site name
- Fully customizable (see [configuration](#configuration))
- Small image size (15-50 Kb) with high resolution and quality ([check it](./docs/examples.md))
- Aspect ratios [presets](#images-aspect-ratios) for popular social networks

[▶ **See examples**](./docs/examples.md)

## Requirements

- PHP 7.4 or higher
- Laravel 8 or higher
- The Imagick PHP extension

## Installation

You can install the package via composer:

```bash
composer require abordage/laravel-og-images
```

You can publish config file with:

```bash
php artisan vendor:publish --tag="og-images-config"
```

You can access the Open Graph Generator using the following facade:

```php
Abordage\LaravelOpenGraphImages\Facades\OpenGraphImages
```

You can set a short alias for this facade in your  `config/app.php` file:

```php
return [
    // ...
    'aliases' => Facade::defaultAliases()->merge([
        // ...
        'OpenGraphImages' => Abordage\LaravelOpenGraphImages\Facades\OpenGraphImages::class,
    ])->toArray()
    // ...
];
```

## Quick start

```php
use Abordage\LaravelOpenGraphImages\Facades\OpenGraphImages;

$text = 'The adventures first, explanations take such a dreadful time!';
$path = \Storage::put('first-og-image.png');

$opengraph = OpenGraphImages::make($text)->save($path);
```
> **Note**  
> All images are encoded in `PNG` format as it provides the best ratio between size/quality. For the same reason, the
> package uses the `Imagick` driver - in tests, it showed an advantage in speed and final size of the generated images.

## Usage

```php
// for <og:image>
OpenGraphImages::make($text)->save($path);
// or
OpenGraphImages::make($text, 'opengraph')->save($path);

// for <twitter:image>
OpenGraphImages::make($text, 'twitter')->save($path);

// for <vk:image>
OpenGraphImages::make($text, 'vk')->save($path);

// custom size
OpenGraphImages::makeCustom($text, 600, 400)->save($path);
```

After generation, you need to somehow organize the relationship of images with a specific page (for example, attach to a
model). If you already have a solution ready to accept an image and attach it to a specific page, you can get the image
as a string instead of saving it:

```php
$imageBlob = OpenGraphImages::make($text)->get();
```

## Usage with `spatie/laravel-medialibrary`
[spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary) is a great package for associate all sorts of files with Eloquent models. If you are using this package (or similar), all you need to do is to add new collections to the model and attach images using media library. 

```php
class Page extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    // ...

    public function registerMediaCollections(): void
    {
        // ...
        
        $this->addMediaCollection('opengraph')
             ->singleFile();
       
        $this->addMediaCollection('twitter')
             ->singleFile();
    }
    
    // ...
}
```

Next, when creating a new page (or updating), generate an og-image and attach it:

```php
$page = new Page();
$page->title = 'Your awesome title';
$page->save();

// generate image and attach to model
$imageBlob = OpenGraphImages::make($page->title)->get();
$page->addMediaFromString($imageBlob)
     ->usingFileName(\Str::slug($page->title) . '.png')
     ->toMediaCollection('opengraph');
```

Multiple images:

```php
$page = new Page();
$page->title = 'Your awesome title';
$page->save();

$presets = ['opengraph', 'twitter', 'vk'];
foreach ($presets as $preset){
    // generate image and attach to model
    $imageBlob = OpenGraphImages::make($page->title, $preset)->get();
    $page->addMediaFromString($imageBlob)
         ->usingFileName(\Str::slug($page->title) . '-' . $preset . '.png')
         ->toMediaCollection($preset);
}
```

Now you can get the link for the og:image meta tag as follows:

```php
$ogImageUrl = $page->getFirstMediaUrl('opengraph');
$twitterImageUrl = $page->getFirstMediaUrl('twitter');
$vkImageUrl = $page->getFirstMediaUrl('vk');
```

## Configuration

```php
$config = [
    /*
    |--------------------------------------------------------------------------
    | Background Color
    |--------------------------------------------------------------------------
    |
    | Supported: HEX, RGB or RGBA format
    |
    */
    'background_color' => '#474761',

    /*
    |--------------------------------------------------------------------------
    | Text Color
    |--------------------------------------------------------------------------
    |
    | Supported: HEX, RGB or RGBA format
    |
    */
    'text_color' => '#eee',

    /*
    |--------------------------------------------------------------------------
    | App Name
    |--------------------------------------------------------------------------
    |
    | Set null to disable
    |
    | Supported: string or null
    |
    */
    'app_name' => config('app.name'),

    /*
    |--------------------------------------------------------------------------
    | App Name Text Color
    |--------------------------------------------------------------------------
    |
    | Supported: HEX, RGB or RGBA format
    |
    */
    'app_name_color' => '#eee',

    /*
    |--------------------------------------------------------------------------
    | App Name Decoration Color
    |--------------------------------------------------------------------------
    |
    | Supported: HEX, RGB or RGBA format
    |
    */
    'app_name_decoration_color' => '#fb3361',

    /*
    |--------------------------------------------------------------------------
    | Text Alignment
    |--------------------------------------------------------------------------
    |
    | Multiline text alignment
    |
    | Supported: "left", "center", "right"
    |
    */
    'text_alignment' => 'left',

    /*
    |--------------------------------------------------------------------------
    | Text Sticky
    |--------------------------------------------------------------------------
    |
    | Supported: "left", "center", "right"
    |
    */
    'text_sticky' => 'center',

    /*
    |--------------------------------------------------------------------------
    | App Name Position
    |--------------------------------------------------------------------------
    |
    | Supported: "top-left", "top-center", "top-right",
    |            "bottom-left", "bottom-center", "bottom-right"
    |
    */
    'app_name_position' => 'bottom-center',

    /*
    |--------------------------------------------------------------------------
    | App Name Decoration Style
    |--------------------------------------------------------------------------
    |
    | Set null to disable
    |
    | Supported: "line", "label", "rectangle", null
    |
    */
    'app_name_decoration_style' => 'line',

    /*
    |--------------------------------------------------------------------------
    | Font Size
    |--------------------------------------------------------------------------
    |
    */
    'font_size' => 55,

    /*
    |--------------------------------------------------------------------------
    | App Name Font Size
    |--------------------------------------------------------------------------
    |
    */
    'app_name_font_size' => 30,

    /*
    |--------------------------------------------------------------------------
    | Text Font
    |--------------------------------------------------------------------------
    |
    | If set null, will be used Preset Font (Roboto Regular)
    |
    | Supported: "absolute/path/to/your/font.ttf", null
    |
    */
    'font_path' => null,

    /*
    |--------------------------------------------------------------------------
    | App Name Font
    |--------------------------------------------------------------------------
    |
    | If set null, will be used Preset Font (Roboto Medium)
    |
    | Supported: "absolute/path/to/your/font.ttf", null
    |
    */
    'app_name_font_path' => null,
];
```

## API Reference

| Method                                                | Returns | Added in | Changed in |
|-------------------------------------------------------|:-------:|:--------:|:----------:|
| `make(string $text, string $preset = 'opengraph')`    |  self   |  0.1.0   |   0.2.0    |
| `makeCustom(string $text, int $width, int $height)`   |  self   |  0.2.0   |     -      |
| `get()`                                               | string  |  0.1.0   |     -      |
| `save(string $path)`                                  | boolean |  0.1.0   |     -      |

### Images aspect ratios

| Preset                            | Aspect ratios       |     Docs      |
|-----------------------------------|:--------------------|:-------------:|
| `make(string $text)`              | 1200 x 630 (1.91:1) |               |
| `make(string $text, 'opengraph')` | 1200 x 630 (1.91:1) |               |
| `make(string $text, 'facebook')`  | 1200 x 630 (1.91:1) |   [fb][fb]    |
| `make(string $text, 'twitter')`   | 1200 x 600 (2:1)    | [twitter][tw] |
| `make(string $text, 'vk')`        | 1200 x 536 (2.2:1)  |   [vk][vk]    |

[fb]: https://developers.facebook.com/docs/sharing/webmasters/images/
[tw]: https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/summary-card-with-large-image
[vk]: https://dev.vk.com/api/posts

## Roadmap

Add ability to use gradients and images for the background.

## Testing

Run all tests

```bash
composer test:all
```

or

```bash
composer test:phpunit
composer test:phpstan
composer test:phpcs
```

## Feedback

Find a bug or have a feature request? Open an issue, or better yet, submit a pull request - contribution welcome!

## Contributing

Please see [CONTRIBUTING](https://github.com/abordage/.github/blob/master/CONTRIBUTING.md) for details.

## Credits

- Pavel Bychko ([abordage](https://github.com/abordage))
- [All Contributors](https://github.com/abordage/laravel-og-images/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
