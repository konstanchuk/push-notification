/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
define(['Magento_Ui/js/form/element/abstract'], function (Abstract) {
    return Abstract.extend({
        defaults: {
            count_transitions: 0
        },

        initialize: function () {
            return this._super();
        },

        initObservable: function () {
            return this._super().observe(['count_transitions']);
        }
    });
});