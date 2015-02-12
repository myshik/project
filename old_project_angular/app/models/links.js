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
    },
    user_id: {
        type: Schema.Types.ObjectId,
        ref: 'id'
    }
});
mongoose.model('Links', linksSchema);
