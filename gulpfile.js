// Load Gulp...of course
const { src, dest, task, watch, series, parallel } = require('gulp');

// CSS related plugins
var sass         = require( 'gulp-sass' )(require('node-sass'));
var autoprefixer = require( 'gulp-autoprefixer' );

// JS related plugins
var uglify       = require( 'gulp-uglify' );
var babelify     = require( 'babelify' );
var browserify   = require( 'browserify' );
var source       = require( 'vinyl-source-stream' );
var buffer       = require( 'vinyl-buffer' );
var stripDebug   = require( 'gulp-strip-debug' );

// Utility plugins
var rename       = require( 'gulp-rename' );
var sourcemaps   = require( 'gulp-sourcemaps' );
var notify       = require( 'gulp-notify' );
var plumber      = require( 'gulp-plumber' );
var options      = require( 'gulp-options' );
var gulpif       = require( 'gulp-if' );
var del			 = require( 'del' );

var filter = require( 'gulp-filter' ); // Enables you to work on a subset of the original files by filtering them using a glob.
var mmq = require( 'gulp-merge-media-queries' ); // Combine matching media queries into one.


// Project related variables
var styleSRC     = './src/scss/site.scss';
var styleURL     = './assets/css/';
var mapURL       = './';

var frameworkSRC = './src/scss/framework.scss';
var adminFrameworkSRC = './src/scss/admin-framework.scss';
var adminSRC	 = './src/scss/admin.scss';

var templatesSRC = './src/scss/pages/templates/*.scss';
var templatesURL = './css/templates/';
var cleanCssGlob = './css/**/*';

var jsSRC        = './src/js/';
var jsFront      = 'site.js';
var jsFiles      = [ jsFront ];
var jsURL        = './assets/js/';
var cleanJsGlob  = './js/**/*';

var styleWatch   = './src/scss/**/*.scss';
var jsWatch      = './src/js/**/*.js';



function globalCss(done) {
	src( [ styleSRC, adminSRC, frameworkSRC, adminFrameworkSRC ] )
		.pipe( plumber() )
		.pipe( sourcemaps.init() )
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'nested'
		}) )
		.on( 'error', console.error.bind( console ) )
		.pipe( autoprefixer({ browsers: [ 'last 2 versions', '> 5%', 'Firefox ESR' ] }) )
		.pipe( mmq({ log: true }) ) 
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'compressed'
		}) )
		.pipe( rename( { suffix: '.min' } ) )	
		.pipe( sourcemaps.write( mapURL ) )
		.pipe( dest( styleURL ) )
	done();
};

function cleanStylesScripts(done){
	return del([ cleanCssGlob, cleanJsGlob ]);
}

function templatesCss(done){

	src( [ templatesSRC ] )
		.pipe( plumber() )
		.pipe( sourcemaps.init() )
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'nested'
		}) )
		.on( 'error', console.error.bind( console ) )
		.pipe( autoprefixer({ browsers: [ 'last 2 versions', '> 5%', 'Firefox ESR' ] }) )
		// .pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
		.pipe( mmq({ log: true }) ) // Merge Media Queries only for .min.css version.
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'compressed'
		}) )
		.pipe( rename( { suffix: '.min' } ) )	
		.pipe( sourcemaps.write( mapURL ) )
		.pipe( dest( templatesURL ) )
	done();
}



function js(done) {
	jsFiles.map( function( entry ) {
		return browserify({
			entries: [jsSRC + entry]
		})
		.transform( babelify, { presets: [ '@babel/preset-env' ] } )
		.bundle()
		// .pipe( plumber() )
		.pipe( source( entry ) )
		.pipe( rename( {
			extname: '.min.js'
        } ) )
		.pipe( buffer() )
		.pipe( gulpif( options.has( 'production' ), stripDebug() ) )
		.pipe( sourcemaps.init({ loadMaps: true }) )
		.pipe( uglify() )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( dest( jsURL ) )
	});
	done();
};



function watch_files() {
	watch(styleWatch, series(globalCss, templatesCss));
	watch(jsWatch, series(js));
	src(jsURL + 'site.min.js')
	.pipe( notify({ message: 'Gulp is Watching, Happy Coding!' }) )
}

task("default", parallel(globalCss, templatesCss, js));
task("rebuild", series( cleanStylesScripts, globalCss, templatesCss, js ));
task("clean", cleanStylesScripts);
task("css", parallel(globalCss, templatesCss));
task("js", js);
task("watch", watch_files);