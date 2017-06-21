/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
define([
    'jquery',
    'Magento_Ui/js/form/components/insert-listing',
    'underscore'
], function ($, Insert, _) {
    'use strict';

    return Insert.extend({
        initialize: function () {
            this._super();
            $('#save').on('click', function (e) {
                e.preventDefault();
                var ids = [];
                $('div[data-index="push_notification_users_details"] .admin__data-grid-wrap .data-grid-checkbox-cell input[type="checkbox"]:checked').each(function () {
                    ids.push($(this).val());
                });
                $('textarea[name="params"]').val(ids.join(';'));
            });
            return this;
        }
    });
});
