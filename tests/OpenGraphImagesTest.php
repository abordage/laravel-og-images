<?php

declare(strict_types=1);

namespace Abordage\LaravelOpenGraphImages\Tests;

use Abordage\LaravelOpenGraphImages\OpenGraphImages;
use Orchestra\Testbench\TestCase as Orchestra;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionException;

class OpenGraphImagesTest extends Orchestra
{
    private OpenGraphImages $openGraphImages;
    private string $directoryPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->openGraphImages = new OpenGraphImages();

        $rootDirName = 'virtualDir';
        vfsStream::setup($rootDirName);
        $this->directoryPath = vfsStream::url($rootDirName);
    }

    #[DataProvider('textProvider')]
    public function testMake(string $text): void
    {
        $result = $this->openGraphImages->make($text);
        $this->assertInstanceOf(OpenGraphImages::class, $result);
        $this->assertEquals('image/png', $this->getMimeTypeFromString($result->get()));

        $presets = ['opengraph', 'facebook', 'twitter', 'vk'];
        foreach ($presets as $preset) {
            $result = $this->openGraphImages->make($text, $preset);
            $this->assertInstanceOf(OpenGraphImages::class, $result);
            $this->assertEquals('image/png', $this->getMimeTypeFromString($result->get()));
        }
    }

    #[DataProvider('textProvider')]
    public function testGetImageSizes(string $text): void
    {
        $openGraphImages = $this->openGraphImages;
        $result = $openGraphImages->getImageSizes();
        $this->assertEquals([], $result);

        $openGraphImages = $this->openGraphImages->make($text);
        $result = $openGraphImages->getImageSizes();

        foreach ($result as $value) {
            $this->assertIsInt($value);
            $this->assertGreaterThan(0, $value);
        }
    }

    #[DataProvider('textProvider')]
    public function testMakeCustom(string $text): void
    {
        $sizesCollection = [
            [500, 500],
            [600, 400],
        ];

        foreach ($sizesCollection as $sizes) {
            [$width, $height] = $sizes;
            $result = $this->openGraphImages->makeCustom($text, $width, $height);
            $this->assertInstanceOf(OpenGraphImages::class, $result);
            $this->assertEquals('image/png', $this->getMimeTypeFromString($result->get()));
        }
    }

    #[DataProvider('textProvider')]
    public function testSave(string $text): void
    {
        $path = $this->directoryPath . '/test1/test2/test-image.png';

        $openGraphImage = $this->openGraphImages->make($text);
        $result = $openGraphImage->save($path);
        $this->assertTrue($result);
        $this->assertEquals('image/png', mime_content_type($path));
    }

    /**
     * @throws ReflectionException
     */
    #[DataProvider('textProvider')]
    public function testMultiLine(string $text): void
    {
        $class = new ReflectionClass(OpenGraphImages::class);
        $method = $class->getMethod('multiLine');
        $obj = new OpenGraphImages();

        $width = 10;
        $result = $method->invoke($obj, $text, $width);
        $this->assertIsString($result);

        $lines = explode("\n", $result);
        foreach ($lines as $line) {
            $this->assertLessThanOrEqual($width, mb_strlen($line));
        }
    }

    /**
     * @param string|null $sting
     * @return false|string
     */
    private function getMimeTypeFromString(?string $sting): false|string
    {
        if (is_null($sting)) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return false;
        }

        return finfo_buffer($finfo, $sting);
    }

    public static function textProvider(): array
    {
        return [
            [
                "The nonprofit Wikimedia Foundation provides the essential infrastructure for free knowledge.
            We host Wikipedia, the free online encyclopedia, created, edited, and verified by volunteers
            around the world, as well as many other vital community projects",
            ],
            ["The Open Graph protocol enables any web page to become a rich object in a social graph"],
            ["Another week of job slashes and crypto crashes"],
        ];
    }
}
