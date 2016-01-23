var gulp = require('gulp'),
    less = require('gulp-less'),
    clean = require('gulp-clean');
    concatJs = require('gulp-concat'),
    minifyJs = require('gulp-uglify');
gulp.task('less', function() {
    return gulp.src(['web_src/less/*.less'])
        .pipe(less({compress: true}))
        .pipe(gulp.dest('web/css/'));
});
gulp.task('images', function () {
    return gulp.src([
            'web_src/img/*'
        ])
        .pipe(gulp.dest('web/img/'))
});
gulp.task('lib-js', function() {
    return gulp.src([
            'bower_components/jquery/dist/jquery.js',
            'bower_components/bootstrap/dist/js/bootstrap.js',
        ])
        .pipe(concatJs('app.js'))
        .pipe(minifyJs())
        .pipe(gulp.dest('web/js/'));
});
gulp.task('pages-js', function() {
    return gulp.src([
            'web_src/js/*.js'
        ])
        .pipe(minifyJs())
        .pipe(gulp.dest('web/js/'));
});

gulp.task('fonts', function () {
    return gulp.src(['bower_components/bootstrap/fonts/*'])
        .pipe(gulp.dest('web/fonts/'))
});

gulp.task('clean', function () {
    return gulp.src(['web/css/*', 'web/js/*', 'web/img/*', 'web/fonts/*'])
        .pipe(clean());
});
gulp.task('default', ['clean'], function () {
    var tasks = ['images', 'less', 'lib-js', 'pages-js', 'fonts'];
    tasks.forEach(function (val) {
        gulp.start(val);
    });
});
gulp.task('watch', function () {
        var less = gulp.watch('web_src/less/*.less', ['less']),
            js = gulp.watch('web_src/js/*.js', ['pages-js']);
});
