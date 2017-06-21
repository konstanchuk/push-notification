/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
define([
    'jquery',
    'Magento_Ui/js/form/element/select'
], function ($, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }_input'
        },
        /**
         * Change currently selected option
         *
         * @param {String} id
         */
        selectOption: function (id) {
            var elem = $('div[data-index="push_notification_users_details"]'),
                value = $("#" + id).val();
            if (value == 0 || value == undefined) {
                elem.hide();
            } else if ($("#" + id).val() == 1) {
                elem.show();
            }
        }
    });
});