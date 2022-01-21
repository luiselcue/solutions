const gulp = require('gulp'),
    postcss = require('gulp-postcss'),
    sass = require('gulp-sass')(require('sass')),
    cssnano = require('cssnano'),
    browserSync = require('browser-sync').create(),
    reload = browserSync.reload;

gulp.task('compile-sass', () => {
    let plugins = [
        cssnano()
    ];

    return gulp.src('sass/*.scss')
        .pipe(sass())
        .pipe(postcss(plugins))
        .pipe(gulp.dest("./"))
        .pipe(reload({stream:true}));
});

gulp.task('reload-browser', (done) => {
    reload();
    done();
});

gulp.task('default', function () {

    browserSync.init({
        proxy: "localhost/solutions/",
        notify: false
    });
    
    gulp.watch("./sass/*.scss", gulp.parallel('compile-sass'));
    gulp.watch("./*.php", gulp.parallel('reload-browser'));
});