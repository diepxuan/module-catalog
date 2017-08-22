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

        options: {
        },

        _create: function() {
            console.log(this);
        }
    });

    return $.mage.positionProducts;
});
