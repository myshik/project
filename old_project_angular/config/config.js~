var path = require('path'),
    rootPath = path.normalize(__dirname + '/..'),
    env = process.env.NODE_ENV || 'development';

var config = {
  development: {
    root: rootPath,
    app: {
      name: 'task5'
    },
    port: 3000,
    db: 'mongodb://localhost/task5-development',
      "app":{
          "url": "http://localhost:3000"
      },
      "auth": {
          "fb": {
              "app_id": '0',
              "secret": "secret"
          },
          "vk": {
              "app_id": 4729114,
              "secret": "RiKyverVlYu0q3b8o4et"
          }
      }
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
    
  },
};

module.exports = config[env];
