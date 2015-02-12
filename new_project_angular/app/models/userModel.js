'use strict';
var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var UserSchema = new Schema({
    email:  String,
    username: String,
    password:   String,
    gender: String
});
mongoose.model('User', UserSchema);