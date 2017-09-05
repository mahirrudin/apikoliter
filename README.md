# Apikoliter

Apikoliter adalah aplikasi web berbasis PHP untuk manajemen server berbasis linux. Idenya mirip seperti webmin + ansible, dimana kita bisa melakukan jobs ansible yang mampu mengubah konfigurasi atau menginstall aplikasi pada server layaknya pada webmin dengan klik. Akses login pada apikoliter dibagi menjadi 2 pengguna, dengan fungsi manager dan sysadmin.

## Bagaimana cara menggunakannya ?
1. Clone/download repositori ini, dan kemudian letakkan di direktori htdocs pada webserver.
2. Buat database dan akses user.
3. Import file sql yang ada pada direktori database/0.initial.tables.sql ke database.
4. Import file sql yang ada pada direktori database/1.initial.views.sql ke database.
5. Ubah file yang ada pada class/dbconnection-samples.php menjadi class/dbconnection.php
6. Edit file class/dbconnection.php dan sesuaikan akses database mysql.
7. Akses melalui web browser.

## Default Login
| No | Username | Password |
| -- | :------: | :------: |
| 1  | it.sysadmin | password |
| 1  | it.manager | password |

## Demo Aplikasi

![Login Apikoliter](/demo/demo_login.jpg?raw=true "Login Apikoliter")