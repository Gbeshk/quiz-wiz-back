# Quiz Wiz — Backend

---

Quiz Wiz is a quiz platform where users can register with email verification, reset their password, and browse, sort, and filter quizzes to attempt. Verified users can also create their own quizzes through the built-in admin panel.

This repository is the **backend/API**, built with Laravel. The frontend (React) lives in a separate repository — see [Resources](#resources) for the link.

#

### Table of Contents

- [Prerequisites](#prerequisites)
- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
- [Development](#development)
- [Deployment](#deployment)
- [Resources](#resources)

#

### Prerequisites

- **PHP** `^8.3`
- **Composer** `^2`
- **Node.js & npm** (for building admin panel / Blade assets)
- **SQLite** (default local database — no separate DB server install required)

#

### Tech Stack

- [Laravel 13](https://laravel.com/docs/13.x) — back-end framework
- **SQLite** — database
- [Laravel Sanctum](https://laravel.com/docs/13.x/sanctum) — API authentication
- [Filament](https://filamentphp.com/) — admin panel (used for quiz creation by verified users)
- [PHP-CS-Fixer](https://redberry.gitbook.io/resources/php-is-linteri) — linter & formatter
- [Vite](https://vitejs.dev/) + [TailwindCSS](https://tailwindcss.com/) — asset bundling & styling for the admin panel / Blade views

#

### Getting Started

1\. Clone the repository:

```sh
git clone https://github.com/RedberryInternship/giorgi-beshkenadze-quiz-wiz-back.git
cd giorgi-beshkenadze-quiz-wiz-back
```

2\. Run the setup script, which installs PHP & JS dependencies, creates your `.env` file, generates the app key, runs migrations, and builds assets — all in one step:

```sh
composer run setup
```

This is equivalent to running manually:

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install --ignore-scripts
npm run build
```

3\. Configure mail settings in your `.env` file. Email verification and password reset rely on outgoing mail — for local development, `MAIL_MAILER=log` is enough to see emails written to `storage/logs/laravel.log` instead of actually sending them:

```env
MAIL_MAILER=log
```

4\. Start the app:

```sh
php artisan serve
```

##### Now, you should be good to go!

#

### Development

To run everything you need locally at once (server, queue worker, logs, and Vite), use:

```sh
composer run dev
```

This runs concurrently:

- `php artisan serve` — the app server
- `php artisan queue:listen` — processes queued jobs (**required for sending emails**, e.g. verification & password reset)
- `php artisan pail` — live log tailing
- `npm run dev` — Vite dev server for admin panel / Blade assets

Before committing, format your code with:

```sh
composer format
```

#

### Deployment

The API is deployed on a VPS behind **Nginx** and **PHP-FPM**, with SSL provided by **Let's Encrypt**.

- **Production API:** [https://quiz-wiz-api.giorgi-beshkenadze.redberryinternship.ge](https://quiz-wiz-api.giorgi-beshkenadze.redberryinternship.ge)
- **Admin panel (Filament):** [https://quiz-wiz-api.giorgi-beshkenadze.redberryinternship.ge/admin](https://quiz-wiz-api.giorgi-beshkenadze.redberryinternship.ge/admin)

Deployment steps, run manually on the server after pulling the latest code:

```sh
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

- The queue worker (`php artisan queue:work`) runs persistently via **Supervisor**, so queued jobs (such as verification and password-reset emails) keep processing in the background.
- Cross-domain, cookie-based Sanctum authentication is configured via `SANCTUM_STATEFUL_DOMAINS` and `SESSION_DOMAIN`, since the frontend and backend are hosted on separate subdomains.
- There is currently no CI/CD pipeline — deploys are manual. This section will be updated if that changes.

#

### Resources

- **Frontend repository:** [quiz-wiz-front](https://github.com/RedberryInternship/giorgi-beshkenadze-quiz-wiz-front)
- **Figma design:** [QuizWiz](https://www.figma.com/design/QTWoxa2OYVayZ04WJ0ZZ9k/QuizWiz?node-id=1057-78034&p=f&t=ZJN3ulmEvhMyNMyY-0)
- **Database diagram:** [DrawSQL — Quiz Wiz schema](https://drawsql.app/teams/giorgi-6/diagrams/quiz-wiz)
