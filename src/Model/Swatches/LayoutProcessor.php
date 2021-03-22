<?php

namespace Magently\CheckoutSwatches\Model\Swatches;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Swatches\Block\Product\Renderer\ConfigurableFactory;
use Magento\Checkout\Model\Session;

/**
 * Class LayoutProcessor
 *
 * This class is responsible for getting swatches js layout for every cart item to render swatches on checkout.
 */
class LayoutProcessor
{
    /**
     * @var Json $json
     */
    private $json;

    /**
     * @var ConfigurableFactory $configurableFactory
     */
    private $configurableFactory;

    /**
     * @var Session $session
     */
    private $session;

    /**
     * LayoutProcessor constructor.
     *
     * @param Json $json
     * @param ConfigurableFactory $configurableFactory
     * @param Session $session
     */
    public function __construct(
        Json $json,
        ConfigurableFactory $configurableFactory,
        Session $session
    ) {
        $this->json = $json;
        $this->configurableFactory = $configurableFactory;
        $this->session = $session;
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getJsLayout()
    {
        $jsLayout = [];

        foreach ($this->session->getQuote()->getItems() as $item) {
            $isConfigurable = $item->getProductType() == Configurable::TYPE_CODE;
            $jsLayout[$item->getItemId()] = [
                'jsonConfig' => $isConfigurable ? $this->getJsonConfig($item) : null,
                'jsonSwatchConfig' => $isConfigurable ? $this->getJsonSwatchConfig($item) : null
            ];
        }

        return $jsLayout;
    }

    /**
     * @param CartItemInterface $item
     * @return string
     */
    public function getJsonConfig(CartItemInterface $item)
    {
        $configurable = $this->configurableFactory->create()
            ->setProduct($item->getProduct());

        return $configurable->getJsonConfig();
    }

    /**
     * @param CartItemInterface $item
     * @return string
     */
    public function getJsonSwatchConfig(CartItemInterface $item)
    {
        $configurable = $this->configurableFactory->create()
            ->setProduct($item->getProduct());

        return $this->filterJsonSwatchConfig($configurable->getJsonSwatchConfig(), $item);
    }

    /**
     * @param CartItemInterface $item
     * @return array
     */
    private function getOptions(CartItemInterface $item)
    {
        $output = [];
        $options = $item->getProduct()->getTypeInstance()->getOrderOptions($item->getProduct());
        $attributesInfo = $options['attributes_info'];

        if (!empty($attributesInfo)) {
            foreach ($attributesInfo as $info) {
                $output[] = $info['value'];
            }
        }

        return $output;
    }

    /**
     * @param string $jsonSwatchConfig
     * @param CartItemInterface $item
     * @return string
     */
    private function filterJsonSwatchConfig(string $jsonSwatchConfig, CartItemInterface $item)
    {
        $output = [];
        $options = $this->getOptions($item);

        foreach ($this->json->unserialize($jsonSwatchConfig) as $primary => $config) {
            foreach ($config as $secondary => $option) {
                if (is_array($option) && in_array($option['label'], $options)) {
                    $output[$primary][$secondary] = $option;
                }
            }
        }

        return $this->json->serialize($output);
    }
}
