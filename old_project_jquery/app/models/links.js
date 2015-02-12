/**
 * Created by user on 11/28/14.
 */
var mongoose = require("mongoose");

Schema = mongoose.Schema;

var linksSchema = new Schema({
    longUrl: {
        type: String,
        default: ""
    },
    shortUrl: {
        type: String,
        default: ""
    },
    checked: {
        type: Boolean,
        default: false
    }
});


mongoose.model('Links', linksSchema);
