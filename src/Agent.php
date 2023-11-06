<?php

namespace Laravel\Jetstream;

use Detection\MobileDetect;

class Agent
{
    /**
     * List of operating systems.
     *
     * @var array
     */
    protected static $operatingSystems = [
        'Windows' => 'Windows',
        'Windows NT' => 'Windows NT',
        'OS X' => 'Mac OS X',
        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'Macintosh' => 'PPC',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'ChromeOS' => 'CrOS',
    ];

    /**
     * List of browsers.
     *
     * @var array
     */
    protected static $browsers = [
        'Opera Mini' => 'Opera Mini',
        'Opera' => 'Opera|OPR',
        'Edge' => 'Edge|Edg',
        'Coc Coc' => 'coc_coc_browser',
        'UCBrowser' => 'UCBrowser',
        'Vivaldi' => 'Vivaldi',
        'Chrome' => 'Chrome',
        'Firefox' => 'Firefox',
        'Safari' => 'Safari',
        'IE' => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Netscape' => 'Netscape',
        'Mozilla' => 'Mozilla',
        'WeChat'  => 'MicroMessenger',
    ];

    /**
     * Construct a new agent.
     *
     * @param  string  $userAgent
     */
    public function __construct(
        protected string $userAgent
    ) {
        //
    }

    public static function getPlatforms()
    {
        return static::mergeRules(
            MobileDetect::getOperatingSystems(),
            static::$operatingSystems
        );
    }

    /**
     * Merge multiple rules into one array.
     *
     * @param  array  $all
     * @return array
     */
    protected static function mergeRules(...$all)
    {
        $merged = [];

        foreach ($all as $rules) {
            foreach ($rules as $key => $value) {
                if (empty($merged[$key])) {
                    $merged[$key] = $value;
                } elseif (is_array($merged[$key])) {
                    $merged[$key][] = $value;
                } else {
                    $merged[$key] .= '|'.$value;
                }
            }
        }

        return $merged;
    }

    public static function getBrowsers()
    {
        return static::mergeRules(
            static::$browsers,
            MobileDetect::getBrowsers()
        );
    }

    public function platform()
    {
        return $this->findDetectionRulesAgainstUserAgent(static::getPlatforms());
    }

    public function browser()
    {
        return $this->findDetectionRulesAgainstUserAgent(static::getBrowsers());
    }

    /**
     * Determine if request from desktop.
     *
     * @return bool
     */
    public function isDesktop()
    {
        $mobileDetect = new MobileDetect();

        $mobileDetect->setUserAgent($this->userAgent);

        return $mobileDetect->isMobile() === false;
    }

    /**
     * Match a detection rule and return the matched key.
     *
     * @param  array  $rules
     * @return string|bool
     */
    protected function findDetectionRulesAgainstUserAgent(array $rules)
    {
        $mobileDetect = new MobileDetect();
        $mobileDetect->setUserAgent($this->userAgent);

        foreach ($rules as $key => $regex) {
            if (empty($regex)) {
                continue;
            }

            if ($mobileDetect->match($regex, $this->userAgent)) {
                return $key;
            }
        }

        return false;
    }
}
