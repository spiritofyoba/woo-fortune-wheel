const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const concat = require('gulp-concat');

const paths = {
    styles: {
        src: 'assets/src/css/*.scss',
        dest: 'assets/dist/css/'
    },
    scripts: {
        src: 'assets/src/js/*.js',
        dest: 'assets/dist/js/'
    },
    bootstrap: {
        css: 'node_modules/bootstrap/dist/css/bootstrap.min.css',
        js: 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'
    }
};

function styles() {
    return src(paths.styles.src)
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(paths.styles.dest));
}

function scripts() {
    return src(paths.scripts.src)
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(paths.scripts.dest));
}

function bootstrapCss() {
    return src(paths.bootstrap.css)
        .pipe(dest(paths.styles.dest));
}

function bootstrapJs() {
    return src(paths.bootstrap.js)
        .pipe(dest(paths.scripts.dest));
}

function watchFiles() {
    watch(paths.styles.src, styles);
    watch(paths.scripts.src, scripts);
}

exports.styles = styles;
exports.scripts = scripts;
exports.bootstrapCss = bootstrapCss;
exports.bootstrapJs = bootstrapJs;
exports.watch = watchFiles;

exports.default = series(
    parallel(styles, scripts, bootstrapCss, bootstrapJs),
    watchFiles
);

exports.build = parallel(styles, scripts, bootstrapCss, bootstrapJs);
