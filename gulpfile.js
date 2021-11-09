const gulp = require("gulp");
const postcss = require("gulp-postcss");
const sass = require("gulp-sass")(require("sass"));
const rucksack = require("rucksack-css");
const imagemin = require("gulp-imagemin");

const autoprefixer = require("autoprefixer");
const tailwindcss = require("tailwindcss");
const cssnano = require("cssnano");

const scssPath = "./apps/**/assets/scss/*.scss";

gulp.task("css", function () {
  const processors = [
    //see https://github.com/ai/browserslist#queries for autoprefixer queries
    tailwindcss("./tailwind.config.js"),
    autoprefixer(),
    rucksack,
    //cssnano //minifies css
  ];
  return gulp
    .src(scssPath)
    .pipe(sass().on("error", sass.logError))
    .pipe(gulp.dest("./public/dist/"))
    .pipe(postcss(processors))
    .pipe(gulp.dest("./public/dist/"));
});

gulp.task("watch:scss", function () {
  return gulp.watch(scssPath, ["css"]);
});

gulp.task("img", function () {
  return gulp
    .src("./apps/**/assets/img/**")
    .pipe(imagemin())
    .pipe(gulp.dest("./public/dist/"));
});
