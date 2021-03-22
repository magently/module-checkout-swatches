<?php

namespace Magently\CheckoutSwatches\Plugin\Checkout\Block\Checkout;

use Magently\CheckoutSwatches\Model\Swatches\LayoutProcessor as SwatchesLayoutProcessor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class LayoutProcessor
 *
 * This class is a plugin for Checkout LayoutProcessor to add swatches JSON configs to details Ui Component.
 */
class LayoutProcessor
{
    /**
     * @var SwatchesLayoutProcessor $swatchesLayoutProcessor
     */
    private $swatchesLayoutProcessor;

    /**
     * LayoutProcessor constructor.
     *
     * @param SwatchesLayoutProcessor $swatchesLayoutProcessor
     */
    public function __construct(SwatchesLayoutProcessor $swatchesLayoutProcessor)
    {
        $this->swatchesLayoutProcessor = $swatchesLayoutProcessor;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $result
     * @return mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    // @codingStandardsIgnoreLine - unused subject
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $result)
    {
        $result['components']['checkout']['children']['sidebar']['children']['summary']['children']['cart_items']
            ['children']['details']['config']['swatchesJsLayout'] = $this->swatchesLayoutProcessor->getJsLayout();

        return $result;
    }
}
