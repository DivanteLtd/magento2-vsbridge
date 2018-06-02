<?php
/**
 * @package   Divante\VsBridge
 * @author    Mateusz Bukowski <mbukowski@divante.pl>
 * @copyright 2018 Divante Sp. z o.o.
 * @license   See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\VsBridge\Setup;

use Divante\VsBridge\Model\Vue\Webhook;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\Store;

/**
 * Class InstallData
 */
class InstallData implements InstallDataInterface
{

    /**
     * @var Config
     */
    private $resourceConfig;

    /**
     * InstallData constructor.
     *
     * @param Config $resourceConfig
     */
    public function __construct(Config $resourceConfig)
    {
        $this->resourceConfig = $resourceConfig;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->generateSecretKey();
        $setup->endSetup();
    }

    /**
     * @return void
     */
    private function generateSecretKey()
    {
        $secretKey = md5(microtime() . mt_rand());

        $this->resourceConfig->saveConfig(
            Webhook::$secretKeyConfigPath,
            $secretKey,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            Store::DEFAULT_STORE_ID
        );
    }
}
