var mongoose = require('mongoose'),
    Schema = mongoose.Schema;

var profileSchema = new Schema({
    FirstName: String,
    LastName: String,
    email: String,
    phone: Number
});

mongoose.model('Profile', profileSchema);
