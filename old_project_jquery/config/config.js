var path = require('path'),
    rootPath = path.normalize(__dirname + '/..'),
    env = process.env.NODE_ENV || 'development';

var config = {
  development: {
    root: rootPath,
    app: {
      name: 'JqueryNodeJS'
    },
    port: 3000,
    db: 'mongodb://localhost/task5-development'
    
  },

  test: {
    root: rootPath,
    app: {
      name: 'task5'
    },
    port: 3000,
    db: 'mongodb://localhost/task5-test'
    
  },

  production: {
    root: rootPath,
    app: {
      name: 'task5'
    },
    port: 3000,
    db: 'mongodb://localhost/task5-production'
    
  }
};

module.exports = config[env];
