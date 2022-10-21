
const { src, dest, watch, series, parallel } = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const less = require('gulp-less');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const imagemin = require('gulp-imagemin');
const replace = require('gulp-replace');

// paths
const files = { 
	lessPath: [	'./src/less/style.less' , // site styles
				'./src/less/admin.less', // styles to adjust look of admin panel
				'./src/less/editor-style.less', // displayed in gutenberg
				'./src/less/login.less' // login modifications
			],
	lessWatchPath: './src/less/**/*.less',
	jsPath: './src/js/**/*.js',
	imgPath: './src/img/**/*',
	output: './assets/',
	outputLess: './'
}

// timing
const cssPollTime = 500;
const jsPollTime = 1000;

// functional tasks
function imgTask() {
	return gulp .src("./img/*")
	.pipe(imagemin())
	.pipe(gulp.dest(files.output));
}

function lessTask(){
	return src(files.lessPath)
		.pipe(sourcemaps.init())
		.pipe(less())
		.pipe(replace('../../assets/', ''))
		.pipe(sourcemaps.write('.'))
		.pipe(dest(files.output) ); 
}

function jsTask(){
	return src([
		files.jsPath,
		'!' + 'src/js/less.js', // to exclude any specific files
		])
		.pipe(concat('scripts.js'))
		.pipe(uglify())
		.pipe(dest(files.output) );
}

function imgTask(){
	return src('./src/img/**/*')
		.pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
		.pipe(dest(files.output) );
}


// watch tasks 
function watchImg(){
	watch([files.imgPath],
		{interval: jsPollTime, usePolling: true},  
		imgTask
	);
}

function watchLess(){
	watch([files.lessWatchPath],
		{interval: cssPollTime, usePolling: true},  
		lessTask
	);
}

function watchJs(){
	watch([files.jsPath],
		{interval: jsPollTime, usePolling: true}, 
		jsTask
	);
}

function watchAll(){
	watch([files.lessWatchPath, files.jsPath, files.imgPath],
		{interval: 2000, usePolling: true}, 
		series(
			parallel(lessTask, jsTask, imgTask)
		)
	);
}


// action commands
// gulp - default watches only js and less
exports.default = series(
	parallel(lessTask, jsTask, imgTask)
);


// gulp watch all - this watchs for js, less, and img changes
exports.watchAll = series(
	parallel(lessTask, jsTask, imgTask), 
	watchAll
);


// gulp watchJs - watches only js
exports.watchJs = series(
	parallel(jsTask), 
	watchJs
);


// gulp watchImg - watches only js
exports.watchImg = series(
	parallel(imgTask), 
	watchImg
);

// single run tasks, gulp css, gulp js, gulp img
exports.less = lessTask;
exports.watchLess = watchLess;
exports.js = jsTask;
exports.img = imgTask;