/**
 * Created by dikhalkin on 22.06.16.
 */
var app = app || {};

app.Orders = Backbone.Collection.extend({
    model: app.Order,
    url: 'http://localhost:8000/api/cabinet/estimates',
    //url: '/app/data/orders.json',
    parse: function (response) {
        return response;
    }
});