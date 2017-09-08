define([
    'jquery',
    'underscore',
    'mage/template',
    'priceUtils',
    'priceBox',
    'jquery/ui',
    'jquery/jquery.parsequery',
    'Magento_ConfigurableProduct/js/configurable',
    'Magento_Ui/js/modal/modal'
], function($, _, mageTemplate) {
    'use strict';

    $.widget('mage.configurable', $.mage.configurable, {

        /**
         * Generate the label associated with a configurable option. This includes the option's
         * label or value and the option's price.
         * @private
         * @param {*} option - A single choice among a group of choices for a configurable option.
         * @return {String} The option label with option value and price (e.g. Black +1.99)
         */
        _getOptionLabel: function(option) {
            console.log(option);
            return option.label;
        },

    });

    return $.mage.configurable;
});
