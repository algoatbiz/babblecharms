var gulp = require('gulp'),
	plumber = require('gulp-plumber'),
	sass = require('gulp-sass'),
	concat = require('gulp-concat'),
	uglify = require('gulp-uglify'),
	notify = require('gulp-notify'),
	bourbon = require('node-bourbon').includePaths,
	neat = require('node-neat').includePaths,
	watch = require('gulp-watch'),
	notify = require('gulp-notify'),
	csso = require('gulp-csso'),
	shorthand = require('gulp-shorthand'),
	autoprefixer = require('gulp-autoprefixer'),
	jshint = require('gulp-jshint'),
	runSequence = require('run-sequence'),
	browserSync = require('browser-sync').create(),
    normalize = require('node-normalize-scss').includePaths;

var paths = {
	scss: './resources/scss/**/*.scss',
	css: './assets/css',
	jsSrc: './resources/js/**/*.js',
	js: './assets/js'
};

gulp.task('browser-sync', function () {
    browserSync.init({
        logPrefix: 'Babble Charms',
        proxy:     'babblecharms.test',
        port:      4075,
        notify:    false,
        open: 	   'external',
        ghost:     false,
        // Change this property with files of your project
        // that you want to refresh the page on changes.
        files:     [
            './**/*.php',
            './content/**/*.txt'
        ]
    });
});

gulp.task('sass', function () {
  return gulp.src(paths.scss)
    .pipe(plumber({errorHandler: notify.onError("SASS error: <%= error.message %> in <%= error.filename %>")}))
    .pipe(sass({
        includePaths: [].concat(bourbon, neat, normalize),
        outputStyle: 'expanded'
    }))
    .pipe(shorthand())
    .pipe(csso())
    .pipe(autoprefixer({
        browsers: ['> 1%'],
        cascade: false
    }))
    .pipe(gulp.dest(paths.css))
    .pipe( browserSync.stream() );
});

gulp.task('uglify', function() {
    return gulp.src(paths.jsSrc)
        .pipe(plumber({errorHandler: notify.onError("JS uglification error: <%= error.message %>")}))
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.js))
        .pipe( browserSync.stream() );
});

gulp.task('build', function() {
  runSequence('sass','uglify');
});

gulp.task('watch', function() {
  gulp.watch(paths.scss, ['sass']);
  gulp.watch(paths.jsSrc, ['uglify']);
});

gulp.task('default', function () {
  runSequence('browser-sync');
  gulp.watch(paths.scss, ['sass']);
  gulp.watch(paths.jsSrc, ['uglify']);
});
