var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();

gulp.task('sass', function(){
	return gulp.src('./sass/**/*.scss')
	.pipe(sass({outputStyle: 'compressed'}))
	.pipe(gulp.dest('./css'));
});

gulp.task('browser-sync', function() {
    browserSync.init({
        server: {
            baseDir: "./"
        }
    });
});

gulp.task('watch', function() {
	gulp.watch('./sass/**/*.scss', gulp.series('sass'));
});

gulp.task('default', gulp.parallel('sass', 'browser-sync', 'watch'));