var gulp = require('gulp'),
    jshint = require('gulp-jshint'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    ngAnnotate = require('gulp-ng-annotate');
    sourcemaps = require('gulp-sourcemaps'),
    sass = require('gulp-sass'),
    csso = require('gulp-csso');
gulp.task('lint', function() {
    gulp.src('../public/js/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});
gulp.task('minify', function(){
    gulp.src('../public/js/**/*.js')
        //.pipe(sourcemaps.init())
        //.pipe(ngAnnotate())
        .pipe(concat('window.js'))
        .pipe(gulp.dest('../public/dist/js/'))
        .pipe(rename('window.min.js'))
        .pipe(uglify())
        //.pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest('../public/dist/js/'));
});
gulp.task('sassCabinet', function() {
    gulp.src('../public/css/cabinet/**/*.scss')
        .pipe(sass())
        .pipe(concat('cabinet.css'))
        .pipe(gulp.dest('../public/dist/css/cabinet/'))
        .pipe(csso())
        .pipe(rename('cabinet.min.css'))
        .pipe(gulp.dest('../public/dist/css/cabinet/'));
});
gulp.task('sassPromo', function() {
    gulp.src('../public/css/promo/**/*.scss')
        .pipe(sass())
        .pipe(concat('promo.css'))
        .pipe(gulp.dest('../public/dist/css/promo/'))
        .pipe(csso())
        .pipe(rename('promo.min.css'))
        .pipe(gulp.dest('../public/dist/css/promo/'));
});
gulp.task('default', function(){
    gulp.run('lint', 'minify', 'sassPromo', 'sassCabinet');

    gulp.watch("../public/js/**/*.js", function(event){
        gulp.run('lint', 'minify');
    });
    gulp.watch("../public/css/promo/**/*.scss", function(event){
        gulp.run('sassPromo', 'minify');
    });
    gulp.watch("../public/css/cabinet/**/*.scss", function(event){
        gulp.run('sassCabinet', 'minify');
    });
});