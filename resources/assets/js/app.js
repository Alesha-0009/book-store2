
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

swal = require('sweetalert');

import Vue from 'vue'
import Vuelidate from 'vuelidate'


window.Vue = require('vue');

window.Vue.use(require('vue-resource'));
window.Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
    request.headers.set('X-Requested-With', 'XMLHttpRequest');
    next();
});

window.Vue.use(require('bootstrap-vue'));
window.Vue.use(Vuelidate);


Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('create-book', require('./components/createBook.vue'));

const app = new Vue({
    el: '#app',

    data:  {
            searcher: {
                books: [],
                loading: false,
                error: false,
                query: ''
            }
        },
    methods: {

        search: function() {

            // Очистим сообщение об ошибке.
            this.searcher.error = '';
            // Опустошим набор данных.
            this.searcher.books = [];
            // Установим признак загрузки данных в true,
            // для отображения процесса поиска "Searching...".
            this.searcher.loading = true;

            // делаем get запрос к нашему API и передаем в него поисковый запрос.
            this.$http.get('/api/search?b=' + this.searcher.query).then((response) => {
                // Елси ошибки нет, заполняем массив books, в случае ошибки заполняем ее
                response.body.error ? this.searcher.error = response.body.error : this.searcher.books = response.body;
                // Запрос завершен. Меняем статус загрузки
                this.searcher.loading = false;
            });
        },
        deleteBook: function(book_id,book_name) {
            this.searcher.error = '';
            swal({
                    title: "Confirm deletion!",
                    text: "Write book name here:",
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "Write book name here"
                },
                function(inputValue){
                    if (inputValue === false) return false;

                    if (inputValue === "" || inputValue != book_name) {
                        swal.showInputError("You need to write book name which you want to delete!");
                        return false
                    }
                    app.$http.get('/api/deleteBook/'+ book_id +'/?c=' + book_name).then((response) => {
                        response.body.error ? this.searcher.error = response.body.error : swal("Nice!", "You have delete book: " + book_name, "success");
                        app.search();
                    });

                });

        }
    },

});
