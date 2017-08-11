"use strict";

var path = {
    build: { //Тут мы укажем куда складывать готовые после сборки файлы
        css: 'build/css/' 
    },
    src: { //Пути откуда брать исходники
        css: 'src/main.css'
    },
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        all: 'build/**/*.*',
        css: 'src/**/*.css'
    },
    clean: './build', //директории которые могут очищаться
    outputDir: './build' //исходная корневая директория для запуска минисервера
};

var gulp = require('gulp'),
  sourcemaps = require('gulp-sourcemaps'),
  rename = require("gulp-rename"),
  rigger = require('gulp-rigger'),
  plumber = require('gulp-plumber'),
  postcss = require('gulp-postcss');

gulp.task('css', function() {
  return gulp.src(path.src.css)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(postcss([
      require("postcss-import"),
      require('postcss-cssnext'),
      // require('cssnano')({ autoprefixer: false })
    ]))
    .pipe(sourcemaps.write())
    .pipe(rename({basename: 'style', suffix: '.test'}))
    .pipe(gulp.dest(path.build.css));
});

gulp.task('build', function() {
  return gulp.src(path.src.css)
    .pipe(postcss([
      require("postcss-import"),
      require('postcss-cssnext'),
      require('cssnano')({ autoprefixer: false })
    ]))
    .pipe(rename({basename: 'style'}))
    .pipe(gulp.dest(path.build.css));
});

gulp.task('watch', function() {
  gulp.watch(path.watch.css, ['css'])
});

gulp.task('default', ['css',  'watch']);