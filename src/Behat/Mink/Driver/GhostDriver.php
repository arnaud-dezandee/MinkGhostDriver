<?php
namespace Behat\Mink\Driver;

use Behat\Mink\Exception\DriverException;
use WebDriver\Exception\UnknownError;
use WebDriver\WebDriver;

/**
 * GhostDriver driver.
 *
 * @author Arnaud Dezandee <arnaudd@theodo.fr>
 */
class GhostDriver extends Selenium2Driver
{
    const PHANTOMJS_PAGE_SETTING_PREFIX       = 'phantomjs.page.settings.';
    const PHANTOMJS_PAGE_CUSTOMHEADERS_PREFIX = 'phantomjs.page.customHeaders.';

    /**
     * @var array
     */
    protected $capabilities = array();

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var array
     */
    protected $customHeaders = array();

    /**
     * Instantiates the driver.
     *
     * @param string $wdHost              The WebDriver host
     * @param array  $settings            Settings of web page for PhantomJS
     * @param array  $customHeaders       Custom Headers to be passed
     */
    public function __construct($wdHost = 'http://localhost:8910/wd/hub', $settings = null, $customHeaders = null)
    {
        $this->setBrowserName('phantomjs');
        $this->setSettings($settings);
        $this->setCustomHeaders($customHeaders);
        $this->setCapabilities();
        $this->setWebDriver(new WebDriver($wdHost));
    }

    /**
     * Sets default capabilities - called on construction.
     */
    public function setCapabilities()
    {
        $this->capabilities = self::getDefaultCapabilities();
    }

    /**
     * @param array $customHeaders
     */
    public function setCustomHeaders($customHeaders = null)
    {
        if (null == $customHeaders) {
            return null;
        }

        $prefixedHeaders = array();
        foreach ($customHeaders as $value) {
            $prefixedHeaders[self::PHANTOMJS_PAGE_CUSTOMHEADERS_PREFIX . $value['name']] = $value['value'];
        }

        $this->customHeaders = $prefixedHeaders;
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings = null)
    {
        if (null == $settings) {
            return null;
        }

        $prefixedSettings = array();
        foreach ($settings as $key => $value) {
            $prefixedSettings[self::PHANTOMJS_PAGE_SETTING_PREFIX . $key] = $value;
        }

        $this->settings = $prefixedSettings;
    }

    /**
     * Returns the default capabilities
     *
     * @return array
     */
    public static function getDefaultCapabilities()
    {
        return array(
            'browserName'               => 'phantomjs',
            'version'                   => '1.9.7',
            'driverName'                => 'ghostdriver',
            'driverVersion'             => '1.1.0',
            'platform'                  => 'ANY',
            'javascriptEnabled'         => true,
            'takesScreenshot'           => true,
            'handlesAlerts'             => false,
            'databaseEnabled'           => false,
            'locationContextEnabled'    => false,
            'applicationCacheEnabled'   => false,
            'browserConnectionEnabled'  => false,
            'cssSelectorsEnabled'       => true,
            'webStorageEnabled'         => false,
            'rotatable'                 => false,
            'acceptSslCerts'            => false,
            'nativeEvents'              => true,
            'proxy' => array(
                'proxyType' => 'direct'
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        try {
            $this->wdSession = $this->webDriver->session(
                $this->browserName,
                array_merge(
                    $this->capabilities,
                    $this->settings,
                    $this->customHeaders
                )
            );
            $this->applyTimeouts();
        } catch (\Exception $e) {
            throw new DriverException('Could not open connection: ' . $e->getMessage(), 0, $e);
        }

        if (!$this->wdSession) {
            throw new DriverException('Could not connect to a PhantomJs / GhostDriver server');
        }
        $this->started = true;
    }
}
