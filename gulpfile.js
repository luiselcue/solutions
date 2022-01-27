const gulp = require('gulp'),
    { parallel } = require('gulp'),
    sass = require('gulp-sass')(require('sass')),
    postcss = require('gulp-postcss'),
    cssnano = require('cssnano'),
    browserSync = require('browser-sync').create();

function processStyles() {
    const plugins = [cssnano()];
    return gulp.src('sass/*.scss').pipe(sass()).pipe(postcss(plugins)).pipe(gulp.dest('./')).pipe(browserSync.stream());
}

function browserLoad() {
    browserSync.init({
        proxy: 'localhost/solutions/',
        port: 8080,
    });
}

function watchForBuildAssets() {
    gulp.watch('sass/*.scss', parallel('processStyles'));
    gulp.watch(['./*.html', './*.php']).on('change', browserSync.reload);
}

exports.processStyles = processStyles;
exports.watchForBuildAssets = watchForBuildAssets;
exports.browserLoad = browserLoad;
exports.default = parallel(this.browserLoad, this.watchForBuildAssets);
