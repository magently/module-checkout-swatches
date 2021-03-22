<?php

namespace Magently\CheckoutSwatches\Plugin\Checkout\CustomerData;

use Magently\CheckoutSwatches\Model\Swatches\LayoutProcessor;
use Magento\Checkout\CustomerData\DefaultItem as Subject;
use Magento\Quote\Model\Quote\Item;

/**
 * This is plugin for DefaultItem class to add new attributes to minicart knockout component.
 */
class DefaultItem
{
    /**
     * @var LayoutProcessor $swatchesLayoutProcessor
     */
    private $swatchesLayoutProcessor;

    /**
     * DefaultItem constructor.
     * @param LayoutProcessor $swatchesLayoutProcessor
     */
    // @codingStandardsIgnoreLine - unused $subject
    public function __construct(LayoutProcessor $swatchesLayoutProcessor)
    {
        $this->swatchesLayoutProcessor = $swatchesLayoutProcessor;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @param Item $item
     * @return array
     */
    public function afterGetItemData(Subject $subject, array $result, Item $item)
    {
        return array_merge(
            [
                'json_config' => $this->swatchesLayoutProcessor->getJsonConfig($item),
                'json_swatch_config' => $this->swatchesLayoutProcessor->getJsonSwatchConfig($item)
            ],
            $result
        );
    }
}
