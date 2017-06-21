/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
define(['jquery', 'Magento_Ui/js/grid/columns/column'], function ($, Column) {
        'use strict';

        return Column.extend({
            defaults: {
                bodyTmpl: 'ui/grid/cells/html',
                fieldClass: {
                    'data-grid-html-cell': true
                }
            },
            hasFieldAction: function () {
                return false;
            }
        });
    }
);
