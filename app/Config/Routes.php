<?php

// ...

use App\Controllers\News;
use App\Controllers\Pages;
use App\Controllers\test;
use App\Controllers\Blog;

$routes->get('/', 'Home::index');
$routes->get('news', [News::class, 'index']);
$routes->get('news/new', [News::class, 'new']); // Add this line
$routes->post('news', [News::class, 'create']); // Add this line
$routes->get('news/(:segment)', [News::class, 'show']);
$routes->get('form', 'Form::index');
$routes->post('form', 'Form::index');
$routes->get('test', [test::class, 'index']);
$routes->get('blog', [Blog::class, 'index']);
$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);