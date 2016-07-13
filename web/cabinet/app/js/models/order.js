/**
 * Created by dikhalkin on 22.06.16.
 */
var app = app || {};

app.Order = Backbone.Model.extend({
    defaults: {
        key: '',
        time: new Date(),
        estimates: [],
        car: {}
    }
});