/**
 * Created by dikhalkin on 22.06.16.
 */
var app = app || {};

app.OrderView = Backbone.View.extend({
    template: _.template($('#orderTemplate').html()),
    render: function() {
        this.$el.html( this.template(this.model.toJSON()) );
        return this;
    },
    events: {
        'click .photo' : 'zoom',
        'click .btn-success': 'changeOrder'
    },
    'zoom': function(e) {
        e.preventDefault();
        $('#preview').attr('src', e.target.src);
        $('#zoom').modal('show');
    },
    'changeOrder': function(e) {

        var orderKey = this.model.get('key');
        var estimate = this.model.get('estimates').pop();
        console.log(estimate);
        var change = new app.ChangeView();
        //$('#changeOrder .modal-body').html(change.render(est).el);
        $('#changeOrder').modal('show');

        var subscribe = new app.Subscribe({
            'order_key': orderKey
        });
        console.log(subscribe.get('order_key'));
    }
});