<?php

namespace Laravel\Jetstream\Tests;

use Laravel\Jetstream\Agent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @copyright Originally created by Jens Segers: https://github.com/jenssegers/agent
 */
class AgentTest extends TestCase
{
    /**
     * @param  string  $userAgent
     * @param  string  $platform
     * @return void
     */
    #[DataProvider('operatingSystemsDataProvider')]
    public function testOperatingSystems($userAgent, $platform)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $this->assertSame($platform, $agent->platform());

        // Test cached value return the same output.
        $this->assertSame($platform, $agent->platform());
    }

    public static function operatingSystemsDataProvider()
    {
        yield ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586', 'Windows'];
        yield ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2', 'OS X'];
        yield ['Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko ) Version/5.1 Mobile/9B176 Safari/7534.48.3', 'iOS'];
        yield ['Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:24.0) Gecko/20100101 Firefox/24.0', 'Ubuntu'];
        yield ['Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+', 'BlackBerryOS'];
        yield ['Mozilla/5.0 (Linux; U; Android 2.2; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 'AndroidOS'];
        yield ['Mozilla/5.0 (X11; CrOS x86_64 6680.78.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.102 Safari/537.36', 'ChromeOS'];
        yield ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36', 'Windows'];
    }

    /**
     * @param  string  $userAgent
     * @param  string  $browser
     * @return void
     */
    #[DataProvider('browsersDataProvider')]
    public function testBrowsers($userAgent, $browser)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $this->assertSame($browser, $agent->browser());

        // Test cached value return the same output.
        $this->assertSame($browser, $agent->browser());
    }

    public static function browsersDataProvider()
    {
        yield ['Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko', 'IE'];
        yield ['Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25', 'Safari'];
        yield ['Mozilla/5.0 (Windows; U; Win 9x 4.90; SG; rv:1.9.2.4) Gecko/20101104 Netscape/9.1.0285', 'Netscape'];
        yield ['Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0', 'Firefox'];
        yield ['Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1667.0 Safari/537.36', 'Chrome'];
        yield ['Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201', 'Mozilla'];
        yield ['Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14', 'Opera'];
        yield ['Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36 OPR/27.0.1689.76', 'Opera'];
        yield ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12', 'Edge'];
        yield ['Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25', 'Safari'];
        yield ['Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36 Vivaldi/1.2.490.43', 'Vivaldi'];
        yield ['Mozilla/5.0 (Linux; U; Android 4.0.4; en-US; LT28h Build/6.1.E.3.7) AppleWebKit/534.31 (KHTML, like Gecko) UCBrowser/9.2.2.323 U3/0.8.0 Mobile Safari/534.31', 'UCBrowser'];
        yield ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36 Edge/15.15063', 'Edge'];
        yield ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.29 Safari/537.36 Edg/79.0.309.18', 'Edge'];
        yield ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/86.0.180 Chrome/80.0.3987.180 Safari/537.36', 'Coc Coc'];
    }

    /**
     * @param  string  $userAgent
     * @param  bool  $expected
     * @return void
     */
    #[DataProvider('devicesDataProvider')]
    public function testDesktopDevices($userAgent, $expected)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $this->assertSame($expected, $agent->isDesktop());
    }

    public static function devicesDataProvider()
    {
        // User Agent, Is Desktop?
        yield ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11) AppleWebKit/601.1.56 (KHTML, like Gecko) Version/9.0 Safari/601.1.56', true];
        yield ['Mozilla/5.0 (iPhone; U; ru; CPU iPhone OS 4_2_1 like Mac OS X; ru) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148a Safari/6533.18.5', false];
        yield ['Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25', false];
        yield ['Mozilla/5.0 (Linux; U; Android 2.3.4; fr-fr; HTC Desire Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', false];
        yield ['Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+', false];
        yield ['Mozilla/5.0 (Linux; U; Android 2.2; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', false];
        yield ['Mozilla/5.0 (Linux; U; Android 4.0.3; en-us; ASUS Transformer Pad TF300T Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30', false];
    }
}
