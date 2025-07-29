<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config;

use Inverse\Termin\Config\ConfigLoader;
use Inverse\Termin\Exceptions\NoConfigFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Tests\Inverse\Termin\TestUtils;

class ConfigLoaderTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('config');
    }

    public function testLoadNoConfig(): void
    {
        self::expectException(NoConfigFoundException::class);
        $loader = new ConfigLoader($this->root->url());
        $loader->load();
    }

    public function testLoadYaml(): void
    {
        $this->root->addChild(
            vfsStream::newFile('config.yml')
                ->withContent(TestUtils::loadFixture('config.yml'))
        );
        $loader = new ConfigLoader($this->root->url());
        $config = $loader->load();
        self::assertEquals(['config' => 'value'], $config);
    }
}
