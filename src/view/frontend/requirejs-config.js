var config = {
    config: {
        'mixins': {
            'Magento_Checkout/js/view/summary/item/details': {
                'Magently_CheckoutSwatches/js/view/summary/item/details': true
            }
        }
    },
    map: {
        '*': {
            'Magento_Checkout/template/minicart/item/default.html':
                'Magently_CheckoutSwatches/template/minicart/item/default.html'
        }
    }
};
