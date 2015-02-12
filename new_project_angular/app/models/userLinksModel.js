var mongoose = require("mongoose");
    Schema = mongoose.Schema;

var userLinkSchema = new Schema({
    longUrl: String,
    shortUrl: String,
    checked: {
        type: Boolean,
        default: false
    },
    user_id: {
        type: Schema.Types.ObjectId,
        ref: 'id'
    }
});

mongoose.model('userLink', userLinkSchema);
