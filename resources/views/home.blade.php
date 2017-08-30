@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card ">
            <div class="input-group">
                <input v-model="searcher.query" type="text" placeholder="What are you looking for?" @keyup.enter="search()" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" v-if="!searcher.loading"  @click="search()">Search!</button>
                    <button class="btn btn-default" type="button" disabled="disabled" v-if="searcher.loading">Searching...</button>
                </span>
            </div>
        </div>
        <div class="alert alert-danger" role="alert" v-if="searcher.error">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            @{{ searcher.error }}
        </div>
        <br>
        <div id="books" class="row list-group-flush">
            <div class="col-sm-3" v-for="item in searcher.books">
                <div class="card">
                    <div class="card-block">
                        <h3 class="card-title">BOOK NAME: @{{ item.Book.name }}</h3>
                        <p class="card-text">Author NAME: @{{ item.Author.name }}</p>
                        <a class="btn btn-danger" id="delete" @click="deleteBook(item.Book.id,item.Book.name)">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
