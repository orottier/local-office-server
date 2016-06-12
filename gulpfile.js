var gulp = require('gulp');
var concat = require('gulp-concat');
var less = require('gulp-less');
var path = require('path');
var cleanCSS = require('gulp-clean-css');

gulp.task('compile', ['compile-less', 'compile-js', 'compile-js-vendor']);

gulp.task('compile-less', function() {
    console.log('🖇  Starting to compile LESS..');
    gulp.src('./resources/assets/less/main.less')
        .pipe(less({
            paths: [path.join(__dirname, 'less', 'includes')]
        }))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/assets/css'));
    console.log('✅  LESS compile complete');
});

gulp.task('compile-js', function() {
    console.log('🖇  Starting to compile JS..');
    gulp.src(['./resources/assets/js/components/*.js'])
        .pipe(concat('components.js'))
        .pipe(gulp.dest('./public/assets/js'));
    console.log('✅  JS compile complete');
});

gulp.task('compile-js-vendor', function() {
    console.log('🖇  Bundling JS..');
    gulp.src(['node_modules/vue/dist/vue.min.js', 'node_modules/vue-resource/dist/vue-resource.min.js', 'node_modules/vue-router/dist/vue-router.min.js'])
        .pipe(concat('vue.js'))
        .pipe(gulp.dest('./public/assets/js'));
    console.log('✅  bundling JS complete');
});

gulp.task('watch', ['watch-less', 'watch-js']);
gulp.task('watch-less', function () {
    gulp.watch('./resources/assets/less/**/*.less', ['compile-less']);
});
gulp.task('watch-js', function () {
    gulp.watch('./resources/assets/js/**/*.js', ['compile-js']);
});

gulp.task('lint', ['lint-less', 'lint-js']);

gulp.task('lint-js', function () {
    console.log('🖇  Checking JS');
    var eslint = require('gulp-eslint');
    var jslint = gulp.src(['./resources/assets/js/components/*.js', './public/script.js'])
        .pipe(eslint({}))
        .pipe(eslint.format())
        .pipe(eslint.failAfterError())
});

gulp.task('lint-less', function () {
    console.log('🖇  Checking LESS');
    var gulpStylelint = require('gulp-stylelint');
    var stylelint = gulp.src('./resources/assets/less/*.less')
        .pipe(gulpStylelint({
            reporters: [{
                formatter: 'string',
                console: true
            }]
        }));
});
