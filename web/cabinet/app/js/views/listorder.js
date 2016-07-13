/**
 * Created by dikhalkin on 23.06.16.
 */
var app = app || {};

app.ListOrder = Backbone.View.extend({
    el: 'div.row',
    initialize: function () {
        this.collection = new app.Orders();
        this.collection.fetch({reset:true});
        this.render();
        this.listenTo(this.collection, 'add', this.renderOrder);
        this.listenTo(this.collection, 'reset', this.render)
    },
    render: function() {
        this.collection.each(function(item) {
            this.renderOrder(item);
        }, this);
    },
    renderOrder: function(item) {
        var orderView = new app.OrderView({
            model: item
        });
        this.$el.append(orderView.render().el);
    }
});