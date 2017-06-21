/**
 * Push Notification Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
define(
    [
        'jquery',
        'uiComponent',
        'underscore',
        'Magento_Customer/js/customer-data'
    ],
    function ($, Component, _, customerData) {

        'use strict';

        return Component.extend({
            defaults: {
                'service_worker': '/sw.js',
                'subscribe_on_btn': false,
                'subscribe_url': '/push-notification/user/subscribe',
                'unsubscribe_url': '/push-notification/user/unsubscribe',
                'statistics_click_url': '/push-notification/statistics/click',
                'subscribe_btn_css_class': '.push-notification-btn',
                'subscribe_btn_text': $.mage.__('subscribe'),
                'unsubscribe_btn_text': $.mage.__('unsubscribe'),
                'local_storage_key': 'push_notification'
            },
            initialize: function () {
                var self = this;
                self._super();
                if (!self.notificationSupported()) {
                    return;
                }
                navigator.serviceWorker.register(self.service_worker).then(function () {
                    if (self.subscribe_on_btn) {
                        $(self.subscribe_btn_css_class).on('click', function (e) {
                            e.preventDefault();
                            self.getSubscription().then(function (subscription) {
                                if (subscription) {
                                    self.unsubscribe();
                                } else {
                                    self.subscribe();
                                }
                            });
                        });
                    }
                    self.getSubscription().then(function (subscription) {
                        if (self.subscribe_on_btn) {
                            self._initSubscribeBtn(subscription);
                        } else {
                            if (!subscription) {
                                self.subscribe();
                            }
                        }
                        if (subscription) {
                            self._initCustomer();
                        }
                    });
                });
                self._statisticsClick();
            },
            _initSubscribeBtn: function (status) {
                if (!this.subscribe_on_btn) {
                    return;
                }
                var text, addClass, removeClass;
                if (status) {
                    text = this.unsubscribe_btn_text;
                    addClass = 'subscribe';
                    removeClass = 'unsubscribe';
                } else {
                    text = this.subscribe_btn_text;
                    addClass = 'unsubscribe';
                    removeClass = 'subscribe';
                }
                $(this.subscribe_btn_css_class)
                    .addClass(addClass)
                    .removeClass(removeClass)
                    .text(text);
            },
            _statisticsClick: function () {

            },
            _initCustomer: function () {
                if (!window.localStorage) {
                    return;
                }
                var self = this,
                    data = window.localStorage.getItem(self.local_storage_key);
                if (!data) {
                    return;
                }
                data = JSON.parse(data);
                if (data && _.has(data, 'customer') && data['customer'] == false) {
                    console.log("RELOAD CUSTOMER", data);
                    console.log(self.isCustomerLoggedIn());
                    self.getSubscription().then(function (subscription) {
                        if (subscription) {
                            $.post(self.subscribe_url, {
                                endpoint: subscription.endpoint
                            }).done(function (data) {
                                if (data.status == 'success') {
                                    window.localStorage.removeItem(self.local_storage_key);
                                }
                            });
                        }
                    });
                }
            },
            isCustomerLoggedIn: function () {
                var getCustomerInfo = function () {
                    var customer = customerData.get('customer');
                    return customer();
                };

                var isLoggedIn = function (customerInfo) {
                    customerInfo = customerInfo || getCustomerInfo();
                    return customerInfo && customerInfo.firstname;
                };

                return function () {
                    var deferred = $.Deferred();
                    var customerInfo = getCustomerInfo();

                    if (customerInfo && customerInfo.data_id) {
                        deferred.resolve(isLoggedIn(customerInfo));
                    } else {
                        customerData.reload(['customer'], false)
                            .done(function () {
                                deferred.resolve(isLoggedIn());
                            })
                            .fail(function () {
                                deferred.fail();
                            });
                    }

                    return deferred;
                };
            },
            notificationSupported: function () {
                return Notification && Notification.permission.toLowerCase() !== 'denied' && 'serviceWorker' in navigator;
            },
            getSubscription: function () {
                return navigator.serviceWorker.ready
                    .then(function (registration) {
                        return registration.pushManager.getSubscription();
                    });
            },
            _getEndpoint: function (subscription) {
                var endpoint = subscription.endpoint;
                var subscriptionId = subscription.subscriptionId;
                if (subscriptionId && endpoint.indexOf(subscriptionId) === -1) {
                    endpoint += '/' + subscriptionId;
                }
                return endpoint;
            },
            _initServiceWorker: function () {
                var self = this;
                navigator.serviceWorker.ready.then(function (register) {
                    register.pushManager.subscribe({
                        userVisibleOnly: true
                    }).then(function (subscription) {
                        self._initSubscribeBtn(true);
                        var endpoint = self._getEndpoint(subscription);
                        var p256dh = subscription.getKey('p256dh');
                        var auth = subscription.getKey('auth');
                        console.log('endpoint:', endpoint);
                        p256dh = p256dh ? btoa(String.fromCharCode.apply(null, new Uint8Array(p256dh))) : null;
                        auth = auth ? btoa(String.fromCharCode.apply(null, new Uint8Array(auth))) : null;
                        $.post(self.subscribe_url, {
                            endpoint: endpoint,
                            p256dh: p256dh,
                            auth: auth
                        }).done(function (data) {
                            //console.log(data);
                        });
                    });
                }).catch(function (error) {
                    console.error($.mage.__('Push Notification Service Worker error:'), error);
                });
            },
            subscribe: function () {
                var self = this;
                if (!self.notificationSupported()) {
                    console.log($.mage.__('Push Notification is not supported'));
                }
                var permission = Notification.permission.toLowerCase();
                if (permission === 'denied') {
                    console.log($.mage.__('Push Notification Permission wasn\'t granted. Allow a retry.'));
                } else if (permission === 'granted') {
                    self._initServiceWorker();
                } else {
                    Notification.requestPermission().then(function (result) {
                        if (result.toLowerCase() == 'granted') {
                            self._initServiceWorker();
                        } else {
                            console.log($.mage.__('Push Notification Permission wasn\'t granted. Allow a retry.'));
                        }
                    });
                }
            },
            unsubscribe: function () {
                var self = this;
                self.getSubscription().then(function (subscription) {
                    return subscription.unsubscribe()
                        .then(function () {
                            var endpoint = self._getEndpoint(subscription);
                            $.post(self.unsubscribe_url, {
                                endpoint: endpoint
                            }).done(function (data) {
                                if (data.status == 'success' && window.localStorage) {
                                    window.localStorage.removeItem(self.local_storage_key);
                                }
                            });
                        });
                }).then(function () {
                    self._initSubscribeBtn(false);
                });
            }
        });
    }
);