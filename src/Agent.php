<?php

namespace Laravel\Jetstream;

use Detection\MobileDetect;

/**
 * @copyright Originally created by Jens Segers: https://github.com/jenssegers/agent
 */
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

    /**
     * Get the platform name from User Agent.
     *
     * @return string|null
     */
    public function platform()
    {
        return $this->findDetectionRulesAgainstUserAgent(
            $this->mergeRules(MobileDetect::getOperatingSystems(), static::$operatingSystems)
        );
    }

    /**
     * Get the browser name from User Agent.
     *
     * @return string|null
     */
    public function browser()
    {
        return $this->findDetectionRulesAgainstUserAgent(
            $this->mergeRules(static::$browsers, MobileDetect::getBrowsers())
        );
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
     * @return string|null
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

        return null;
    }

    /**
     * Merge multiple rules into one array.
     *
     * @param  array  $all
     * @return array
     */
    protected function mergeRules(...$all)
    {
        $merged = [];

        foreach ($all as $rules) {
            foreach ($rules as $key => $value) {
                if (empty($merged[$key])) {
                    $merged[$key] = $value;
                } elseif (is_array($merged[$key])) {
                    $merged[$key][] = $value;
                } else {
                    $merged[$key] .= '|' . $value;
                }
            }
        }

        return $merged;
    }
}
