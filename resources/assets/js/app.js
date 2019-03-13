/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import ImageCarousel from "./components/ImageCarousel";
import ScopedSlotComponent from "./components/ScopedSlotComponent";

Vue.component('blog-post', {
    // camelCase in JavaScript
    props: ['post', 'name'],
    template: '<div><slot></slot><button @click="change">{{ post.text + post.name}}{{name}}</button></div>',
    methods: {
        change() {
            this.name = 'New name';
        }
    }
});

Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('ref-component', {
    template: '<div>Ref component <ImageCarousel></ImageCarousel></div>',
    components: {
        ImageCarousel,
    }
});

const app = new Vue({
    el: '#app',
    data: {
        text: 'Hello VueJs',
        post: {text: 'Test', name: 'Name'},
        name: 'Name',
        todos: [{text: 'Lear01', id: 12}, {text: 'Lear02', id: 23}]
    },
    methods: {
        test: function () {
            alert(1)
        }
    },
    components: {
        ImageCarousel,
        ScopedSlotComponent
    }
});
window.vm = app;
