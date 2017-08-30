<template>
    <div class="container">

        <div class="alert alert-danger" role="alert" v-if="error">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            @{{ error }}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">Create new book</div>

                    <div class="card-block">
                        <div class="form-group">
                            <label for="book">Book name:</label>
                            <input type="text" class="form-control" id="book" v-model="bookName">
                            <small>Name will be: {{ bookName }}</small>
                        </div>
                        <div class="form-group">
                            <label for="book">Abs:</label>
                            <input type="text" class="form-control" id="abs" v-model="abs">
                            <small>Abs will be: {{ abs }}</small>
                        </div>
                        <div class="form-group">
                            <label>Author:</label>
                            <select class="form-control" v-model="selectedAuthors">
                                <option v-for="author in authors">
                                    {{ author.name }}
                                </option>
                            </select>
                            <small>Select author name or <a href="#createAuthor" v-b-toggle.collapse>create</a> new</small>
                            <b-collapse id=collapse class="mt-2">
                                <b-card>
                                    <b-alert :show="dismissCountDown"
                                             variant="success"
                                             @dismissed="dismissCountdown=0"
                                             @dismiss-count-down="countDownChanged">
                                        <p>Author successfully added!</p>
                                        <b-progress variant="success"
                                                    :max="dismissSecs"
                                                    :value="dismissCountDown"
                                                    height="4px">
                                        </b-progress>
                                    </b-alert>
                                    <div class="container">
                                        <div class="form-group" v-bind:class="{ 'has-danger': $v.authorName.$error }">
                                            <label for="book">Author name:</label>
                                            <input type="text" class="form-control" id="authorName" v-model.trim="authorName" @input="$v.authorName.$touch()">
                                            <div class="form-control-feedback" v-if="!$v.authorName.required">Field is required</div>
                                            <div class="form-control-feedback" v-if="!$v.authorName.minLength">Name must have at least {{$v.authorName.$params.minLength.min}} letters.</div>
                                        </div>
                                        <div class="form-group" v-bind:class="{ 'has-danger': $v.authorLastName.$error }">
                                            <label for="book">Author Last name:</label>
                                            <input type="text" class="form-control" id="authorLastName" v-model.trim="authorLastName" @input="$v.authorLastName.$touch()">
                                            <div class="form-control-feedback" v-if="!$v.authorLastName.required">Field is required</div>
                                            <div class="form-control-feedback" v-if="!$v.authorLastName.minLength">Name must have at least {{$v.authorLastName.$params.minLength.min}} letters.</div>
                                        </div>
                                    </div>
                                    <b-card-footer v-if="!$v.authorName.$invalid && !$v.authorLastName.$invalid">
                                        <b-button variant="outline-success" @click="createAuthor()" >Create</b-button>
                                    </b-card-footer>
                                </b-card>
                            </b-collapse>

                        </div>
                        <div class="form-group">
                            <label>Genre:</label>
                            <select class="form-control" v-model="selectedGenres" multiple>
                                <option v-for="genre in genres">
                                    {{ genre.name }}
                                </option>
                            </select>
                            <small>Select genere or <a href="#createGenre" v-b-toggle.collapseGenre>create</a> new</small>
                            <b-collapse id=collapseGenre class="mt-2">
                                <b-card>
                                    <b-alert :show="dismissCountDown"
                                             variant="success"
                                             @dismissed="dismissCountdown=0"
                                             @dismiss-count-down="countDownChanged">
                                        <p>Genre successfully added!</p>
                                        <b-progress variant="success"
                                                    :max="dismissSecs"
                                                    :value="dismissCountDown"
                                                    height="4px">
                                        </b-progress>
                                    </b-alert>
                                    <div class="container">
                                        <div class="form-group" v-bind:class="{ 'has-danger': $v.genreName.$error }">
                                            <label for="book">Genre name:</label>
                                            <input type="text" class="form-control" id="genreName" v-model.trim="genreName" @input="$v.genreName.$touch()">
                                            <div class="form-control-feedback" v-if="!$v.genreName.required">Field is required</div>
                                            <div class="form-control-feedback" v-if="!$v.genreName.minLength">Name must have at least {{$v.genreName.$params.minLength.min}} letters.</div>
                                        </div>
                                    </div>
                                    <b-card-footer v-if="!$v.genreName.$invalid">
                                        <b-button variant="outline-success" @click="createGenre()" >Create</b-button>
                                    </b-card-footer>
                                </b-card>
                            </b-collapse>

                        </div>
                    </div>
                    <b-card-footer v-if="!$v.bookName.$invalid && !$v.selectedAuthors.$invalid && !$v.selectedGenres.$invalid">
                        <b-button variant="outline-success" @click="publishBook()" >Create</b-button>
                    </b-card-footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var swall = require('sweetalert');
    import { required, minLength, between } from 'vuelidate/lib/validators'
    export default {
        data(){
            return {
                dismissSecs: 2,
                dismissCountDown: 0,
                showDismissibleAlert: false,
                authors: [],
                genres: [],
                authorName: '',
                genreName: '',
                selectedAuthors: '',
                selectedGenres: [],
                authorLastName: '',
                error: '',
                info: '',
                bookName: '',
                abs: ''
            }
        },
        validations: {
            authorName: {
                required,
                minLength: minLength(4)
            },
            authorLastName: {
                required,
                minLength: minLength(4)
            },
            genreName: {
                required,
                minLength: minLength(4)
            },
            selectedAuthors: {
                required,
            },
            selectedGenres: {
              required,
            },
            bookName: {
                required,
                minLength: minLength(4)
            },
        },
        methods: {
            getAuthors(){
                this.$http.get('/api/getAuthors').then((response) => {
                    if(response.body.error)
                        this.error = response.body.error[0]
                    else
                        this.authors = response.body;
                });
                console.log('Success api.getAuthors.');
            },
            getGenres(){
                this.$http.get('/api/getGenres').then((response) => {
                    if(response.body.error)
                        this.error = response.body.error[0]
                    else
                        this.genres = response.body;
                });
                console.log('Success api.getGenres.');
            },
            clicked(){
                console.log(this.authors);
            },
            clearName() {
                this.name = '';
            },
            createAuthor(){
                var data = {name: this.authorName, surname: this.authorLastName};
                this.$http.post('/api/createAuthor',data).then((response) => {
                    if(response.body.error)
                        this.error = response.body.error[0]
                    else
                        this.info = response.body.success;
                    this.selectedAuthors = response.body.author;
                    this.getAuthors();
                    this.showAlert();
                });
            },
            createGenre(){
                var data = {name: this.genreName};
                this.$http.post('/api/createGenre',data).then((response) => {
                    if(response.body.error)
                        this.error = response.body.error[0]
                    else
                        this.info = response.body.success;
                    this.selectedGenres.push(response.body.genre);
                    this.getGenres();
                    this.showAlert();
                });
            },
            publishBook(){
                var data = {name: this.bookName,abs:this.abs, author: this.selectedAuthors, genres: this.selectedGenres};
                this.$http.post('/api/publishBook',data).then((response) => {
                    if(response.body.error)
                        this.error = response.body.error[0]
                    else
                        this.info = response.body.success;
                    console.log(response);
               //     this.$route.router.go('/');
                });
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },
            showAlert() {
                this.dismissCountDown = this.dismissSecs;
            }
        },
        created: function(){
            this.getAuthors();
            this.getGenres();

            console.log('Component created.')

        },


    }
</script>
