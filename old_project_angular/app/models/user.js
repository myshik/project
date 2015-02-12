var mongoose = require('mongoose');
    Schema = mongoose.Schema;
var UserSchema = new Schema({
    username:{
      type: String
    },
    gender:{
        type: String
    },
    email: {
        type: String
    },
    password: {
        type: String
    }
});
mongoose.model('user', UserSchema);