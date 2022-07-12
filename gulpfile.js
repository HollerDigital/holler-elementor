'use strict';

// DEPENDENCIES =======================================================
var gulp        = require('gulp');
var babel 		= require('gulp-babel');
var browserSync = require('browser-sync').create();
var $           = require('gulp-load-plugins')();
var debug       = require('gulp-debug');
var del         = require('del');
var minifyCSS   = require('gulp-minify-css');
var size        = require('gulp-filesize');
var reload      = browserSync.reload;
var argv        = require('yargs').argv;
var autoprefixer = require('gulp-autoprefixer');
var uglify      = require('gulp-uglify');
var concat = require('gulp-concat');
var gutil = require('gulp-util');
var gulpif = require('gulp-if');
var sourcemaps = require('gulp-sourcemaps');
var notify = require("gulp-notify");

// FILE PATHS =========================================================

var source = {
  vendor  : 'source/vendor',
  style   : 'source/styles/',
  styles  : 'source/styles/**/**/*.scss',
  scripts : 'source/scripts/*.js',
  images  : 'source/images/*.{png,jpg,gif}',
  sprite  : 'source/images/*.png',
  svgs    : 'source/images/*.svg'
};

var assets = {
  theme   : '',
  styles  : 'assets/css',
  scripts : 'assets/js',
  images  : 'assets/img'
};


// Browser Sync Config =========================================================
var bsconfig = {
    // Customize the BrowserSync console logging prefix
    logPrefix: '⎋',
    proxy:  "http://192.168.67.19",
    //logFileChanges: true,
    open: "local", //"local", "external", "ui", "ui-external" or "tunnel"
    notify: true,
    injectChanges: true
  }

// AUTOPREFIXER CONFIG ================================================
var AUTOPREFIXER_BROWSERS = [
  'ie >= 8',
  'ie_mob >= 10',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4.4',
  'bb >= 10'
];
 

// COMPILE STYLESHEETS ================================================
gulp.task('styles', function () {
  return gulp.src(source.style + '/styles.scss')
    .pipe($.changed('styles', {
      extension: '.scss'
    }))
    .pipe(sourcemaps.init())
    .pipe($.sass({
      precision: 5,
      outputStyle: 'expanded',
      onError: function(err) {
           console.log(assets.theme);
          return notify().write(err);
        },
      stopOnError: false,
      sourcemap: true,
      verbose: true,
      emitCompileError: true,
      lineNumbers: true,
      includePaths: [
        source.styles,
        //source.vendor + '/bootstrap-sass-official/assets/stylesheets',
      ]
    }))
    .on('error', $.sass.logError)
    .pipe($.autoprefixer({
      browsers: AUTOPREFIXER_BROWSERS
    }))
    .pipe(sourcemaps.write('maps', {
      includeContent: false,
      sourceRoot: 'source'
    }))
    //.pipe( concat('brandt-elementor-app.css') )
    .pipe(gulp.dest(assets.styles))
    .pipe($.rename({ suffix: '.min' }))
  	.pipe(minifyCSS({keepBreaks:true}))
  	.pipe(gulp.dest(assets.styles))
    .pipe($.size({title: 'styles'}))
    .pipe($.notify({ message: 'Styles task complete' }))
    .pipe(browserSync.stream());

});

// Vendor Plugin Scripts ================================================
gulp.task('plugins', function() {
  return gulp.src([
    source.vendor + '/jquery-ui-1.12.1.custom/jquery-ui.min.js',
    source.vendor + '/js-cookie/src/js.cookie.js',
    source.vendor + '/OwlCarousel2-2.2.1/dist/owl.carousel.js',
    source.vendor + '/imagesloaded/imagesloaded.pkgd.min.js',
    source.vendor + '/jQuery.mmenu-master/dist/jquery.mmenu.all.min.js',
    source.vendor + '/fastclick/lib/fastclick.js',
    source.vendor + '/scrollreveal-master/dist/scrollreveal.min.js',
    source.vendor + '/typeahead.js/dist/typeahead.bundle.js',
    source.scripts + '/plugins.js',
  ])
  //.pipe($.concat('plugins.js'))
  //.pipe(gulp.dest(assets.scripts))
  .pipe($.concat('plugins.js'))
  .pipe(gulp.dest(assets.scripts))
  .pipe($.rename({ suffix: '.min' }))
  .pipe($.uglify())
  //.pipe($.concat('plugins.min.js'))
  .pipe(sourcemaps.write('maps/'))
  .pipe(gulp.dest(assets.scripts))
  .pipe($.notify({ message: 'Plugins task complete' }));

});

// LINT & CONCATENATE JS ==============================================
gulp.task('js', function () {
  return gulp.src(source.scripts)
    .pipe(sourcemaps.init())
    .pipe(babel({
        presets: ['@babel/env']
    }))
    .pipe( $.jshint() )
    .pipe( $.jshint.reporter('jshint-stylish') )
    .pipe( concat('brandt-elementor-app.js') )
    .pipe( gulp.dest(assets.scripts))
    .pipe($.rename({ suffix: '.min' }))
    .pipe($.uglify())
    .pipe(sourcemaps.write('maps/'))
    .pipe(gulp.dest(assets.scripts))
    .pipe($.notify({ message: 'JS task complete' }));
});

// WATCH FOR CHANGES AND RELOAD =======================================
gulp.task('serve', ['styles'], function () {

  //browserSync.init(bsconfig);
  gulp.watch(['**/**/*.php']).on("change", reload);
  gulp.watch([source.styles], ['styles']);
  gulp.watch([source.images], ['images']).on("change", reload);
  gulp.watch([source.scripts], ['js']).on("change", reload);
});

// Default Task =======================================
gulp.task('default',  function() {
    gulp.start('serve','styles', 'plugins', 'js');
});

