# Anastiana Dewi (220302029)

## CodeIgniter4

## Selamat Datang di CodeIgniter4
CodeIgniter merupakan sebuah framework PHP yang digunakan untuk membangun web atau application. Tujuannya adalah untuk memudahkan pengembangan project jauh lebih cepat dari pada menulis kode dari awal.

Persyaratan Server
- Gunakan PHP versi 7.4 atau lebih baru
- Aktifkan ekstensi PHP internasional, mbstring, json
- Basis data yang didukung : MySQL versi 5.1 ke atas, Oracle Database versi 12.1 ke atas, dsb. (MySQL dan Oracle Oracle Database merupakan database yang sering dugunakan)

## Instalasi Komposer
Instalasi ini memiliki kelebihan sederhana dan mudah untuk diperbarui.

Langkah - langkah instalasi :
- Pastikan composer versi 2.0.14 atau lebih sudah terinstal
    
    `composer -v`
- Tentukan dan buka folder dimana kita akan meletakkan project
- Buka terminal pada folder tersebut
- Perintah untuk membuat project baru
    
    ```
    composer create-project codeigniter4/appstarter namaproject
    ```
- Perintah untuk mengupdate
    
    `composer update`

## Intalasi Manual
Instalasi ini memiliki kelebihan yaitu hanya perlu unduh dan jalankan.

Langkah - langkah :
- Download starter project dari repository.
- Ekstrak folder uang sudah di unduh.

## Menjalankan Aplikasi Anda
#### Konfigurasi Awal
Langkah - langkah :
- Buka **app/Config/App.php**
- Tetapkan **$baseURL**  atau dapat diatur pada file env
    
    `app.baseURL = 'http://localhost:8080/'`
- Tetapkan **$indexPage** (Jika tidak ingin menyertakan index.php di URI situs anda, setel `$indexPage = ''`)
- Buka file **env**, ubah nama file env menjadi **.env** (supaya file env dapat digunkan)
- Tetapkan ke metode **Development** pada file **.env** kemudian aktifkan (supaya ketika terjadi error muncul keterangan errornya)
    
    `CI_ENVIRONMENT = development`

#### Menjalankan Project
Langkah - langkah :
- Buka terminal pada folder project kita
- Masukkan baris perintah
    
    `php spark serve`
- Klik pada server yang ada
    
    `http://localhost:8080`

## Halaman Statis
Hal pertama yang akan dilakukan adalah menyiapkan aturan perutean untuk menangani halaman statis.
#### Menetapkan aturan perutean
- Buka **app/Config/Routes.php**
- Tambahkan baris perintah
```
use App\Controllers\Pages;

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);
```
#### Buat pengontrol halaman
- Buat **app/Controllers/Pages.php**
- Masukkan baris perintah
```
<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function view($page = 'home')
    {
        // ...
    }
}
```
#### Buat tampilan
- Buat **app/Views/templates/header.php** dan masukkan baris perintah
```
<!doctype html>
<html>
<head>
    <title>CodeIgniter Tutorial</title>
</head>
<body>

    <h1><?= esc($title) ?></h1>
```
- Buat **app/Views/templates/footer.php** dan masukkan baris perintah
```
 <em>&copy; 2022</em>
</body>
</html>
```
#### Menambahkan logika ke Controllers
- Buat file **home.php** dan **about.php** pada **app/Views/pages** masukkan baris perintah seperti **"Hello World!"**. Sekarang kunjungi **localhost:8080/home** atau kunjungi **localhost:8080/about**
- Pada controllers **Pages.php** tambahkan baris perintah
```
<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException; // Add this line

class Pages extends BaseController
{
    // ...

    public function view($page = 'home')
    {
        if (! is_file(APPPATH . 'Views/pages/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            throw new PageNotFoundException($page);
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        return view('templates/header', $data)
            . view('pages/' . $page)
            . view('templates/footer');
    }
}
```
Sekarang kunjungi **localhost:8080/home**

## News Section
#### Buat database untuk digunakan
- Buat database pada MySQL dan jalankan perintah
```
CREATE TABLE news (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(128) NOT NULL,
    slug VARCHAR(128) NOT NULL,
    body TEXT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE slug (slug)
);
```
- Isikan data ke tabel news
```
INSERT INTO news VALUES
(1,'Elvis sighted','elvis-sighted','Elvis was sighted at the Podunk internet cafe. It looked like he was writing a CodeIgniter app.'),
(2,'Say it isn\'t so!','say-it-isnt-so','Scientists conclude that some programmers have a sense of humor.'),
(3,'Caffeination, Yes!','caffeination-yes','World\'s largest coffee shop open onsite nested coffee shop for staff only.');
```
#### Hubungkan dengan Basis Data
- Aktifkan baris perintah berikut pada file **.env**
```
database.default.hostname = localhost
database.default.database = ci4tutorial
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```
#### Buat model berita
- Buat file **NewsModel.php** pada **app/Models**, masukkan baris perintah berikut dan tambahkan fungsi **getNews()**
```
<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';

    public function getNews($slug = false)
    {
        if ($slug === false) {
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }
}
```
#### Menambahkan aturan perutean
- Pada **app/Config/Routes.php** tambahkan baris perintah berikut
```
<?php

// ...

use App\Controllers\News; // Add this line
use App\Controllers\Pages;

$routes->get('news', [News::class, 'index']);           // Add this line
$routes->get('news/(:segment)', [News::class, 'show']); // Add this line

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);
```
#### Buat pengontrol berita
- Buat controler baru **app/Controllers/News.php** dengan baris perintah berikut
```
<?php

namespace App\Controllers;

use App\Models\NewsModel;

class News extends BaseController
{
    public function index()
    {
        $model = model(NewsModel::class);

        $data['news'] = $model->getNews();
    }

    public function show($slug = null)
    {
        $model = model(NewsModel::class);

        $data['news'] = $model->getNews($slug);
    }
}
```
- Lengkapi News::index()Method dengan baris perintah berikut
```
<?php

namespace App\Controllers;

use App\Models\NewsModel;

class News extends BaseController
{
    public function index()
    {
        $model = model(NewsModel::class);

        $data = [
            'news'  => $model->getNews(),
            'title' => 'News archive',
        ];

        return view('templates/header', $data)
            . view('news/index')
            . view('templates/footer');
    }

    // ...
}
```
#### Buat file tampilan news/index
- Buat **app/Views/news/index.php** dan masukkan baris perintah berikut
```
<h2><?= esc($title) ?></h2>

<?php if (! empty($news) && is_array($news)): ?>

    <?php foreach ($news as $news_item): ?>

        <h3><?= esc($news_item['title']) ?></h3>

        <div class="main">
            <?= esc($news_item['body']) ?>
        </div>
        <p><a href="/news/<?= esc($news_item['slug'], 'url') ?>">View article</a></p>

    <?php endforeach ?>

<?php else: ?>

    <h3>No News</h3>

    <p>Unable to find any news for you.</p>

<?php endif ?>
```
#### Lengkapi News::show()Method
```
<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    // ...

    public function show($slug = null)
    {
        $model = model(NewsModel::class);

        $data['news'] = $model->getNews($slug);

        if (empty($data['news'])) {
            throw new PageNotFoundException('Cannot find the news item: ' . $slug);
        }

        $data['title'] = $data['news']['title'];

        return view('templates/header', $data)
            . view('news/view')
            . view('templates/footer');
    }
}
```
#### Buat news/view file
- Buat **app/Views/news/view.php** dengan baris perintah berikut
```
<h2><?= esc($news['title']) ?></h2>
<p><?= esc($news['body']) ?></p>
```
Sekarang kunjungi **localhost:8080/news**

## Buat Item Berita (Create News Items)
#### Aktifkan filter CSRF
- Buka file **app/Config/Filters.php** dan perbarui baris perintah berikut
```
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    // ...

    public $methods = [
        'post' => ['csrf'],
    ];

    // ...
}
```
#### Menambahkan aturan perutean
- Tambahkan baris perintah berikut pada **app/Config/Routes.php**
```
<?php

// ...

use App\Controllers\News;
use App\Controllers\Pages;

$routes->get('news', [News::class, 'index']);
$routes->get('news/new', [News::class, 'new']); // Add this line
$routes->post('news', [News::class, 'create']); // Add this line
$routes->get('news/(:segment)', [News::class, 'show']);

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);
```
#### Buat formulir
- Buat tampilan baru pada **app/Views/news/create.php** dengan baris perintah berikut
```
<h2><?= esc($title) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= validation_list_errors() ?>

<form action="/news" method="post">
    <?= csrf_field() ?>

    <label for="title">Title</label>
    <input type="input" name="title" value="<?= set_value('title') ?>">
    <br>

    <label for="body">Text</label>
    <textarea name="body" cols="45" rows="4"><?= set_value('body') ?></textarea>
    <br>

    <input type="submit" name="submit" value="Create news item">
</form>
```
- Tambahkan **function new** pada **app/Controllers/News.php**
```
<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    // ...

    public function new()
    {
        helper('form');

        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/create')
            . view('templates/footer');
    }
}
```
- Tambahkan **function create** pada **app/Controllers/News.php**
```
<?php

namespace App\Controllers;

use App\Models\NewsModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class News extends BaseController
{
    // ...

    public function create()
    {
        helper('form');

        $data = $this->request->getPost(['title', 'body']);

        // Checks whether the submitted data passed the validation rules.
        if (! $this->validateData($data, [
            'title' => 'required|max_length[255]|min_length[3]',
            'body'  => 'required|max_length[5000]|min_length[10]',
        ])) {
            // The validation fails, so returns the form.
            return $this->new();
        }

        // Gets the validated data.
        $post = $this->validator->getValidated();

        $model = model(NewsModel::class);

        $model->save([
            'title' => $post['title'],
            'slug'  => url_title($post['title'], '-', true),
            'body'  => $post['body'],
        ]);

        return view('templates/header', ['title' => 'Create a news item'])
            . view('news/success')
            . view('templates/footer');
    }
}
```
- Buat tampilan pada **app/Views/news/success.php** dengan baris perintah berikut (isi konten dapat diubah sesuai kebutuhan)
```
<p>News item created successfully.</p>
```
#### Pembaruan model berita 
- Tammbahkan **$allowedFields** pada **app/Models/NewsModel** dengan baris perintah berikut
```
<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';

    protected $allowedFields = ['title', 'slug', 'body'];
}
```
Sekarang kunjungi **localhost:8080/news/new**

## Struktur Aplikasi
#### Default Directories
- app, tempat semua kode aplikasi berada. Folder app membentuk beberapa konten dasar yaitu : config, controllers, database, filters, helpers, language, libraries, models, thirdparty, view.
- system, menyimpan file-file yang membentuk kerangka itu sendiri. Meski memiliki banya fleksibilitas dalam cara menggunakan direktori aplikasi, file dalam direktori sistem tidak boleh diubah.
- vendor, berisi file yang digunakan framework.
- public, menampung bagian aplikasi web yang dapat diakses browser, mencegah akses langsung ke kode sumber.
- writable, menampung semua direktori yang mungkin perlu ditulisi selama masa pakai aplikasi. Ini termasuk direktori untuk menyimpan file cache, log, dan unggahan apa pun yang mungkin dikirim pengguna.
- test, untuk menyimpan file pengujian.

## Models, Views, and Controllers (MVC)
CodeIgniter menggunakan pola Model, View, Controller (MVC) untuk mengatur file. untuk mengatur kode agar mudah menemukan file yang tepat dan memudahkan pemeliharaan.
- Models, mengelola data aplikasi dan membantu menegakkan aturan bisnis khusus yang mungkin diperlukan aplikasi.
- View, adalah file sederhana, dengan sedikit atau tanpa logika, yang menampilkan informasi kepada pengguna.
- Controllers, bertindak sebagai kode perekat, menyusun data bolak-balik antara tampilan (atau pengguna yang melihatnya) dan penyimpanan data.
Cara kerja MVC : Controller menerima permintaan dari pengguna lalu berinteraksi dengan Model database jika perlu kemudian mengembalikan hasilnya kembali ke browser dalam bentuk kode HTML yang ditafsirkan oleh browser menjadi format yang dapat dibaca manusia dan ditampilkan kepada pengguna.
## VIEW
Membuat tampilan
- Masuk ke **app/Views** buat file **blog_view.php** dengan baris perintah berikut
```
<html>
    <head>
        <title>My Blog</title>
    </head>
    <body>
        <h1>Welcome to my Blog!</h1>
    </body>
</html>
```
Menampilkan tampilan
- Masuk ke **app/Controllers** buat file **Blog.php** dengan baris perintah berikut
```
<?php

namespace App\Controllers;

class Blog extends BaseController
{
    public function index()
    {
        return view('blog_view');
    }
}
```
- Tambahkan baris perintah berikut pada **app/Config/Routes.php**
```
use App\Controllers\Blog;

$routes->get('blog', [Blog::class, 'index']);
```
Sekarang kunjungi **localhost:8080/blog**

## Helpers
**Date Helper**

File Helper Tanggal berisi fungsi yang membantu dalam bekerja dengan tanggal. Berikut kode helper Date
```
<?php

helper('date');
```
Penerapan :
- Buat file **test.php** pada **app/view** dengan baris perintah
```
<?php

helper('date');
echo date('Y-M-d H:i:s', now('Asia/Jakarta'));

?>
```
- Buat file **test.php** pada **app/Controllers** dengan baris perintah
```
<?php

namespace App\Controllers;

class test extends BaseController
{
    public function index(): string
    {
        return view('test');
    }
}
```
- Tambah baris perintah pada **app/Config/Routes**
```
<?php
use App\Controllers\test;
$routes->get('test', [test::class, 'index']);
```
Sekarang kunjungi **localhost:8080/test**
**Number Helper**

File Number Helper berisi fungsi yang membantu Anda bekerja dengan data numerik. Berikut kode helper nomor
```
<?php

helper('number');
```
Penerapan :
- Buat file **test.php** pada **app/view** dengan baris perintah
```
<?php
helper('number');
echo number_to_size(1);
echo number_to_size(456); // Returns 456 Bytes
echo number_to_size(4567); // Returns 4.5 KB
echo number_to_size(45678); // Returns 44.6 KB
echo number_to_size(456789); // Returns 447.8 KB
echo number_to_size(3456789); // Returns 3.3 MB
echo number_to_size(12345678912345); // Returns 1.8 GB
echo number_to_size(123456789123456789); // Returns 11,228.3 TB
//var_dump(number_to_size(5));
```
- Buat file **test.php** pada app/Controllers dengan baris perintah sama seperti date helper, atau menggunakan file test.php yang sudah ada.
- Tambah baris perintah pada **app/Config/Routes** dengan baris perintah seperti sebelumnya. Jika sudah ada tidak perlu ditambahkan.
Sekarang kunjungi **localhost:8080/test**
