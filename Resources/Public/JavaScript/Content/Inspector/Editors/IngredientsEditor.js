define(
[
    'emberjs',
    'Library/jquery-with-dependencies',
    'text!./IngredientsEditor.html',
    'text!./IngredientElement.html'
],
function (Ember, $, template, templateIngredient) {

    return Ember.View.extend({

        template: Ember.Handlebars.compile(template),

        ingredients: [],

        ingredientsView: Ember.CollectionView.extend({
            tagName: 'div',
            content: [],

            init: function () {
                this._super();
                this.set('ingredients', Ember.A());
            },

            itemViewClass: Ember.View.extend({
                tagName: 'div',
                classNames: ['fv-inspector-ingredient'],
                template: Ember.Handlebars.compile(templateIngredient),
                remove: function() {
                    this.get('_parentView._parentView').removeIngredient(this.get('content'));
                }
            }),

            emptyView: Ember.View.extend({
                tagName: 'div',
                classNames: ['fv-ember-noingredients'],
                template: Ember.Handlebars.compile('No ingredients')
            })

        }),

        addIngredient: function () {
            newIngredient = {"name": "Mehl", "amount": "22", "unit": "g"};
            this.get('ingredients').addObject(newIngredient);
            this._updateValue();
        },

        removeIngredient: function(ingredient) {
            this.get('ingredients').removeObject(this.get('ingredients').findProperty('name', ingredient.name));
            this._updateValue();
        },

        _updateValue: function() {
            console.log(this.get('ingredients'));
            this.set('value', 'Zutatenliste' + Math.random());
        }


    })

});
