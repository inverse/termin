<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config;

use Inverse\Termin\Config\ConfigParser;
use Inverse\Termin\Config\Notifier\Ntfy;
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
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('config missing sites key');
        $this->configParser->parse([]);
    }

    public function testParseEmptySites(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('config has no sites defined');
        $this->configParser->parse(['sites' => []]);
    }

    public function testParseSiteMissingType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('site missing type field');
        $this->configParser->parse(['sites' => [
            [
                'label' => 'Important',
            ],
        ]]);
    }

    public function testParseSiteMissingLabel(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('site missing label field');
        $this->configParser->parse(['sites' => [
            [
                'url' => 'https://important.com',
            ],
        ]]);
    }

    public function testParseSiteMissingParams(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('site missing params field');
        $this->configParser->parse(['sites' => [
            [
                'label' => 'Important',
                'type' => 'foo',
            ],
        ]]);
    }

    public function testParseSiteParamsNotArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('site.params field must be array');
        $this->configParser->parse(['sites' => [
            [
                'label' => 'Important',
                'type' => 'foo',
                'params' => 'bar',
            ],
        ]]);
    }

    public function testParseValid(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig());

        self::assertCount(1, $config->getSites());
        self::assertEquals('Important', $config->getSites()[0]->getLabel());
        self::assertEquals(['param_1' => 'value_1'], $config->getSites()[0]->getParams());
        self::assertFalse($config->isAllowMultipleNotifications());
        self::assertNull($config->getTelegram());
        self::assertNull($config->getPushbullet());
    }

    public function testParseAllowMultipleNotifications(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + ['allow_multiple_notifications' => true]);
        self::assertTrue($config->isAllowMultipleNotifications());
    }

    public function testParseNtfyEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('config.ntfy missing topic field');
        $this->configParser->parse($this->getBasicConfig() + [
            'ntfy' => [
            ],
        ]);
    }

    public function testParseNtfyValidDefaultServer(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'ntfy' => [
                'topic' => 'termin_fun',
            ],
        ]);

        $ntfy = $config->getNtfy();
        if (null === $ntfy) {
            self::fail('Ntfy config must not be null');
        }

        self::assertEquals(Ntfy::DEFAULT_SERVER, $ntfy->getServer());
        self::assertEquals('termin_fun', $ntfy->getTopic());
    }

    public function testParseNtfyValid(): void
    {
        $config = $this->configParser->parse($this->getBasicConfig() + [
            'ntfy' => [
                'server' => 'https://my-server.com',
                'topic' => 'termin_fun',
            ],
        ]);

        $ntfy = $config->getNtfy();
        if (null === $ntfy) {
            self::fail('Ntfy config must not be null');
        }

        self::assertEquals('https://my-server.com', $ntfy->getServer());
        self::assertEquals('termin_fun', $ntfy->getTopic());
    }

    public function testParseTelegramEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('config.telegram missing api_key field');
        $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
            ],
        ]);
    }

    public function testParseTelegramMissingApiKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('config.telegram missing api_key field');
        $this->configParser->parse($this->getBasicConfig() + [
            'telegram' => [
                'chat_id' => '1',
            ],
        ]);
    }

    public function testParseTelegramMissingChatId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('config.telegram missing chat_id field');
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

        $telegram = $config->getTelegram();
        if (null === $telegram) {
            self::fail('Telegram config must not be null');
        }

        self::assertEquals('api', $telegram->getApiKey());
        self::assertEquals(1, $telegram->getChatId());
    }

    public function testParsePushbulletEmpty(): void
    {
        self::expectException(\InvalidArgumentException::class);
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

        $pushbullet = $config->getPushbullet();
        if (null === $pushbullet) {
            self::fail('Pushbullet config must not be null');
        }

        self::assertEquals('token', $pushbullet->getApiToken());
    }

    public function testParseRulesNotArray(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('rules must be an array');
        $this->configParser->parse($this->getBasicConfig() + [
            'rules' => 'foo',
        ]);
    }

    public function testParseRulesRuleNotArray(): void
    {
        self::expectException(\InvalidArgumentException::class);
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
        self::expectException(\InvalidArgumentException::class);
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
        self::expectException(\InvalidArgumentException::class);
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
                    'type' => 'foo',
                    'params' => [
                        'param_1' => 'value_1',
                    ],
                ],
            ],
        ];
    }
}
