<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config;

use InvalidArgumentException;
use Inverse\Termin\Config\ConfigParser;
use Inverse\Termin\Config\Rules\AfterDateRule;
use Inverse\Termin\Config\Rules\AfterRule;
use Inverse\Termin\Config\Rules\BeforeDateRule;
use Inverse\Termin\Config\Rules\BeforeRule;
use Monolog\Level;
use PHPUnit\Framework\TestCase;

class ConfigParserTest extends TestCase
{
    private ConfigParser $configParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configParser = new ConfigParser();
    }

    public function testParseEmpty(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('config missing sites key');
        $this->configParser->parse([]);
    }

    public function testParseEmptySites(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('config has no sites defined');
        $this->configParser->parse(['sites' => []]);
    }

    public function testParseSiteMissingUrl(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('site missing url field');
        $this->configParser->parse(['sites' => [
            [
                'label' => 'Important',
            ],
        ]]);
    }

    public function testParseSiteMissingLabel(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('site missing label field');
        $this->configParser->parse(['sites' => [
            [
                'url' => 'https://important.com',
            ],
        ]]);
    }

    public function testParseValid(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig());

        self::assertCount(1, $config->getSites());
        self::assertEquals($config->getSites()[0]->getLabel(), 'Important');
        self::assertEquals($config->getSites()[0]->getUrl(), 'https://important.com');
        self::assertFalse($config->isAllowMultipleNotifications());
        self::assertNull($config->getTelegram());
        self::assertNull($config->getPushbullet());
    }

    public function testParseAllowMultipleNotifications(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + ['allow_multiple_notifications' => true]);
        self::assertTrue($config->isAllowMultipleNotifications());
    }

    public function testParseTelegramEmpty(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('config.telegram missing api_key field');
        $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
            ],
        ]);
    }

    public function testParseTelegramMissingApiKey(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('config.telegram missing api_key field');
        $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
                'chat_id' => '1',
            ],
        ]);
    }

    public function testParseTelegramMissingChatId(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('config.telegram missing chat_id field');
        $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
                'api_key' => 'api',
            ],
        ]);
    }

    public function testParseTelegramEmptyValue(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
                'api_key' => null,
                'chat_id' => '1',
            ],
        ]);

        self::assertNull($config->getTelegram());
    }

    public function testParseTelegramNull(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
                'api_key' => null,
                'chat_id' => null,
            ],
        ]);

        self::assertNull($config->getTelegram());
    }

    public function testParseTelegramValid(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
                'api_key' => 'api',
                'chat_id' => '1',
            ],
        ]);

        self::assertEquals($config->getTelegram()->getApiKey(), 'api');
        self::assertEquals($config->getTelegram()->getChatId(), 1);
    }

    public function testParsePushbulletEmpty(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('config.pushbullet missing api_token field');
        $this->configParser->parse($this->getBasicConfig() + [
            'pushbullet' => [
            ],
        ]);
    }

    public function testParsePushbulletEmptyValue(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'pushbullet' => [
                'api_token' => null,
            ],
        ]);

        self::assertNull($config->getPushbullet());
    }

    public function testParsePushbulletValid(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'pushbullet' => [
                'api_token' => 'token',
            ],
        ]);

        self::assertEquals($config->getPushbullet()->getApiToken(), 'token');
    }

    public function testParseRulesNotArray(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('rules must be an array');
        $this->configParser->parse($this->getBasicConfig() + [
            'rules' => 'foo',
        ]);
    }

    public function testParseRulesRuleNotArray(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('rule must be an array');
        $this->configParser->parse($this->getBasicConfig() + [
            'rules' => [
                'random',
            ],
        ]);
    }

    public function testParseRuleAfter(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'rules' => [
                [
                    'type' => 'after',
                    'param' => 'PT24H',
                ],
            ],
        ]);

        self::assertNotEmpty($config->getRules());
        self::assertEquals(new AfterRule('PT24H'), $config->getRules()[0]);
    }

    public function testParseRuleBefore(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'rules' => [
                [
                    'type' => 'before',
                    'param' => 'PT24H',
                ],
            ],
        ]);

        self::assertNotEmpty($config->getRules());
        self::assertEquals(new BeforeRule('PT24H'), $config->getRules()[0]);
    }

    public function testParseRuleAfterDate(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'rules' => [
                [
                    'type' => 'after_date',
                    'param' => '2022-01-01 00:00:00',
                ],
            ],
        ]);

        self::assertNotEmpty($config->getRules());
        self::assertEquals(new AfterDateRule('2022-01-01 00:00:00'), $config->getRules()[0]);
    }

    public function testParseRuleBeforeDate(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'rules' => [
                [
                    'type' => 'before_date',
                    'param' => '2022-01-01 00:00:00',
                ],
            ],
        ]);

        self::assertNotEmpty($config->getRules());
        self::assertEquals(new BeforeDateRule('2022-01-01 00:00:00'), $config->getRules()[0]);
    }

    public function testParseRulesInvalidRuleType(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('foo is an invalid rule type');
        $this->configParser->parse($this->getBasicConfig() + [
            'rules' => [
                [
                    'type' => 'foo',
                    'param' => 'PT24H',
                ],
            ],
        ]);
    }

    public function testParseLogLevelDefault(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig());
        self::assertEquals(Level::Info, $config->getLogLevel());
    }

    public function testParseLogLevelOverride(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'logger' => [
                'level' => 'debug',
            ],
        ]);
        self::assertEquals(Level::Debug, $config->getLogLevel());
    }

    public function testParseLogLevelInvalid(): void
    {
        self::expectException(InvalidArgumentException::class);
        $this->configParser->parse($this->getBasicConfig() + [
            'logger' => [
                'level' => 'foobar',
            ],
        ]);
    }

    private function getBasicConfig(): array
    {
        return [
            'sites' => [
                [
                    'label' => 'Important',
                    'url' => 'https://important.com',
                ],
            ],
        ];
    }
}
