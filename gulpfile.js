var gulp = require('gulp');
var concat = require('gulp-concat');
var less = require('gulp-less');
var path = require('path');
gulp.task('compile', function() {
 console.log('Launching rocket to compile.. 🚀');

 console.log('🖇  Starting to compile LESS..');

   gulp.src('./resources/assets/less/main.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('./public/assets/css'));
   console.log('✅  LESS compile complete');



// JS
   console.log('🖇  Starting to compile JS..');
    gulp.src(['./resources/assets/js/components/*.js'])
      .pipe(concat('components.js'))
      .pipe(gulp.dest('./public/assets/js'));

    gulp.src(['node_modules/vue/dist/vue.min.js', 'node_modules/vue-resource/dist/vue-resource.min.js',  'node_modules/vue-router/dist/vue-router.min.js'])
      .pipe(concat('vue.js'))
      .pipe(gulp.dest('./public/assets/js'));
   console.log('✅  JS compile complete');
});
