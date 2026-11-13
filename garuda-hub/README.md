# Garuda-Hub (Laravel / MVC)

Refactor dari versi PHP native (`../src/index.php`) ke **Laravel** dengan arsitektur **MVC** dan **Eloquent ORM**.
Fitur sama: klien memasang proyek, developer menawar, klien memilih pemenang. Semua data adalah data dummy.

## Skema relasional

```
users (id, nama, email*, password, role)
  |-- 1:N --> projects (id, client_id -> users.id, judul, deskripsi, kategori,
  |                     budget_min, budget_max, batas_lelang, status)
  |-- 1:N --> bids     (id, project_id -> projects.id, developer_id -> users.id,
                        harga, durasi, proposal, is_winner)   UNIQUE(project_id, developer_id)
projects --- 1:N --> bids
```

Semua foreign key memakai `ON DELETE CASCADE`. Migration ada di `database/migrations`.

## Pemetaan legacy ke MVC

| PHP native (`index.php`)                              | Laravel                                                                   |
| ----------------------------------------------------- | ------------------------------------------------------------------------- |
| query `mysqli` manual                                 | Model Eloquent: `User`, `Project`, `Bid` (+ relasi)                       |
| `page_home`, `page_project`, `page_post`, `project_*` | `ProjectController` + `ProjectRequest` + `ProjectPolicy`                  |
| `page_bid`, `page_pick`, `bid_*`                      | `BidController` + `StoreBidRequest`                                       |
| `page_dashboard`                                      | `DashboardController`                                                     |
| `page_admin`, `admin_*`                               | `AdminController`                                                         |
| `auth_*`, `need_role()`                               | `AuthController`, `RegisterRequest`, middleware `EnsureRole` (`role:...`) |
| HTML di dalam string PHP                              | Blade di `resources/views`                                                |
| `?page=...`                                           | `routes/web.php`                                                          |
| `database.sql`                                        | migration + `DummyDataSeeder`                                             |

## Menjalankan

Butuh PHP 8.3+, Composer, dan MySQL/MariaDB.

```bash
cd garuda-hub
composer install
cp .env.example .env      # Windows PowerShell: copy .env.example .env
php artisan key:generate
# buat database `garuda_hub`, sesuaikan DB_* di .env
php artisan migrate:fresh --seed
php artisan serve
```

- `migrate:fresh` menghapus semua tabel lalu migrate ulang; `--seed` menjalankan seeder.
- Password akun dummy tidak dicantumkan. Daftar akun baru, lalu jadikan admin bila perlu:

```bash
php artisan tinker --execute="App\Models\User::where('email', 'EMAIL_KAMU')->update(['role' => 'admin']);"
```

## Versi Laravel (MVC)

Refactor ke Laravel (MVC + Eloquent ORM) ada di folder [`garuda-hub/`](garuda-hub/). Cara menjalankan dan pemetaan dari versi native ada di [`garuda-hub/README.md`](garuda-hub/README.md).
