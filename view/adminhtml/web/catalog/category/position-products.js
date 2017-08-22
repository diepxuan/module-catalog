/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'jquery/ui'
], function($) {
    'use strict';

    $.widget('mage.positionProducts', {

        options: {},

        _create: function _create() {
            var self = this;

            $('[data-position-placeholder=content]', this.element).sortable({
                update: function update(event, ui) {
                    self._reindex();
                }
            });

            self._bindHtml();
        },

        _reindex: function _reindex() {
            var self = this;

            $('.admin__position-item', self.element).each(function(index, element) {
                $('.admin__position-position', this).html(index + 1);
            });
        },

        _bindHtml: function _bindHtml() {
            var self = this;

            $('[data-position-action=reindex]', self.element).on('click', function reindex_click() {
                self._reindex();
            });
        }
    });

    return $.mage.positionProducts;
});
