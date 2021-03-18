define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('mage.apiRefreshButton', {
        options: {
            url: ''
        },

        _create: function () {
            this._on({
                'click': $.proxy(this.changeApiKey, this)
            });
        },

        changeApiKey: function () {
            $.ajax({
                url: this.options.url,
                data: {form_key: FORM_KEY}
            }).done(function (response) {
                let newApiKey = response;
                let valueElement = document.querySelector('#row_koality_api_key_api_key .value')
                let oldApiKey = valueElement.innerHTML.match('.+?(?=<p)')
                valueElement.innerHTML = valueElement.innerHTML.replace(oldApiKey, newApiKey)
            })
        }
    });

    return $.mage.apiRefreshButton;
});
