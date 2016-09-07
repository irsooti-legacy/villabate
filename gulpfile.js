/* eslint-env node */
var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var htmlmin = require('gulp-htmlmin');
var eslint = require('gulp-eslint');
var imagemin = require('gulp-imagemin');
var clean = require('gulp-clean');
var jasmine = require('gulp-jasmine-phantom');
var phantomjs = require('phantomjs');
 

/* Important Source and Dist */

var folder = {
	source: {
		css: 'source/css/**/*.css',
		sass: 'source/sass/**/*.scss',
		js: [
			'bower_components/jquery/dist/jquery.min.js',
			'bower_components/bootstrap/dist/js/bootstrap.min.js',
			'source/js/**/*.js'
		],
		fonts: [
			'node_modules/bootstrap-sass/assets/fonts/**/*.{ttf,otf,woff,woff2,svg,eot}',
			'bower_components/font-awesome/fonts/**/*.{ttf,otf,woff,woff2,svg,eot}'
		],
		img: 'source/img/**/*.{png,jpg,jpeg,svg}'
	},

	dist: {
		css: 'dist/css',
		js: 'dist/js',
		fonts: 'dist/fonts',
		img: 'dist/img',
		html: 'dist/*.php'
	}
};


gulp.task('default', ['clear', 'sass', 'copy-fonts', 'opti-img', 'lint', 'uglify', 'html-minify'], function() {
	
});


gulp.task('dist', function() {

});

gulp.task('unitTests', function () {
  return gulp.src('test/spec/appTest.js')
          .pipe(jasmine());
});

gulp.task('clear', function () { 
	return gulp.src([folder.dist.css, folder.dist.js, folder.dist.fonts, folder.dist.img, folder.dist.html], {read: false})
	.pipe(clean());
});

gulp.task('lint', function() {
	// ESLint ignores files with "node_modules" paths. 
	// So, it's best to have gulp ignore the directory as well. 
	// Also, Be sure to return the stream from the task; 
	// Otherwise, the task may end before the stream has finished. 
	return gulp.src([folder.source.js[2]])
	// eslint() attaches the lint output to the "eslint" property 
	// of the file object so it can be used by other modules. 
	.pipe(eslint())
	// eslint.format() outputs the lint results to the console. 
	// Alternatively use eslint.formatEach() (see Docs). 
	.pipe(eslint.format())
	// To have the process exit with an error code (1) on 
	// lint error, return the stream and pipe to failAfterError last. 
	.pipe(eslint.failAfterError());
});

gulp.task('opti-img', function() { 
	gulp.src(folder.source.img)
        .pipe(imagemin())
        .pipe(gulp.dest(folder.dist.img));
});

gulp.task('copy-fonts', function() {
	gulp.src(folder.source.fonts)
	.pipe(gulp.dest(folder.dist.fonts));
});

gulp.task('uglify', function() {
	return gulp.src(folder.source.js)
		.pipe(uglify())
		.pipe(concat('app.js'))
		.pipe(gulp.dest(folder.dist.js));
});

gulp.task('sass', function() {
	return gulp.src(folder.source.sass)
	.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
	.pipe(sourcemaps.init())
	.pipe(autoprefixer({ browsers: ['last 4 versions'],
            cascade: false
        }))
	.pipe(sourcemaps.write('.'))
	.pipe(gulp.dest(folder.dist.css));
});

gulp.task('html-minify', function() {  return gulp.src('source/*.php')
    .pipe(htmlmin({collapseWhitespace: true}))
    .pipe(gulp.dest('dist'));
});