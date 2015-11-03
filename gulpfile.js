// Base Gulp.

var gulp = require('gulp');
var less = require('gulp-less');
var watch = require('gulp-watch');
var minifycss = require('gulp-minify-css');
var rename = require('gulp-rename');
var gzip = require('gulp-gzip');
var livereload = require('gulp-livereload');

var gzip_options = {
    threshold: '1kb',
    gzipOptions: {
        level: 9
    }
};

/* Compile Our Sass */
gulp.task('less', function() {
    return gulp.src('scss/*.scss')
        .pipe(less())
        .pipe(gulp.dest('static/stylesheets'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss())
        .pipe(gulp.dest('static/stylesheets'))
        .pipe(gzip(gzip_options))
        .pipe(gulp.dest('static/stylesheets'))
        .pipe(livereload());
});

/* Watch Files For Changes */
gulp.task('watch', function() {
    livereload.listen();
    gulp.watch('scss/*.scss', ['less']);

});

gulp.task('default', ['less', 'watch']);
