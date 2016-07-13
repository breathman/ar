/**
 * Created by dikhalkin on 05.07.16.
 */
var app = app || {};

app.ChangeView = Backbone.View.extend({
    template: _.template($('#change').html()),
    render: function() {
        this.$el.html(this.template());
        return this;
    }
});