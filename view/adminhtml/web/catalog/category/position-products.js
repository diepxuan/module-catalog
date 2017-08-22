/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* global $, $H */

define([
    'jquery',
    'jquery/ui'
], function(jQuery) {
    'use strict';

    jQuery.widget('mage.positionProducts', {

        options: {
            positionProducts: {}
        },

        _create: function _create() {
            this.positionProducts = $H(this.options.positionProducts);

            var self = this;

            self._bindHtml();
        },

        _reindex: function _reindex() {
            var self = this;

            jQuery('.admin__position-item', self.element).each(function(index, element) {
                var $position = index + 1;
                var $id = jQuery('.admin__position-id', this).text().trim();
                jQuery('.admin__position-position', this).html($position);

                self.positionProducts.set($id, $position);
            });

            self._reloadValue();
        },

        _bindHtml: function _bindHtml() {
            var self = this;

            jQuery('[data-position-placeholder=content]', self.element).sortable({
                update: function update(event, ui) {
                    self._reindex();
                }
            });

            jQuery('[data-position-action=reindex]', self.element).on('click', function reindex_click() {
                self._reindex();
            });
        },

        _reloadValue: function _reloadValue() {
            jQuery('#in_position_products').val(Object.toJSON(this.positionProducts));
            $('in_position_products').value = Object.toJSON(this.positionProducts);
        }
    });

    return jQuery.mage.positionProducts;
});
