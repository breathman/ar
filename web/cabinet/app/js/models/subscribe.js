/**
 * Created by m.dykhalkin on 12.07.2016.
 */
var app = app || {};

app.Subscribe = Backbone.Model.extend({
    defaults: {
        order_key: '',
        service_key: 'AAAP',
        cost: null,
        note: ''
    },
    initialize: function() {
        console.log('Будет создана подписка на заказ ' + this.order_key);
    }
});