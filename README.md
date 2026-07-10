# Central Zakat Management (CZM) Platform

The Central Zakat Management Platform is a comprehensive, modern, and Shariah-compliant digital system built with Laravel 11. It aims to connect Zakat donors, volunteers, partner organizations, and beneficiaries in a transparent, efficient, and accountable way.

## 🚀 Features

- **Multi-Role Authentication & Dashboard**: Custom dashboards for Super Admins, Zakat Officers, Partner Organizations, Volunteers, Donors, and Beneficiaries.
- **Donor Portal**: Transparent Zakat calculator, online payment integration, and personal ledger.
- **Beneficiary Management**: Shariah-compliant applicant tracking, verification workflows, and fund distribution records.
- **Volunteer & Organization Network**: Robust onboarding, verification, and activity logging for on-ground volunteers and partner organizations.
- **Superadmin Controls**: Deep control over user roles, activity logs, platform settings, and account impersonation for debugging and support.
- **Dynamic Settings**: Manage Shariah rates (Nisab based on Gold/Silver), currency, and system UI settings easily from the admin panel.
- **Responsive UI/UX**: Built with Bootstrap 5, featuring a beautiful glassmorphism design, dark mode by default, and fully responsive layouts.

## 🛠 Tech Stack

- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8.0
- **Cache & Queue**: Redis
- **Frontend**: Blade Templating, Bootstrap 5, Vanilla CSS
- **Containerization**: Docker & Docker Compose

## 💻 Developer Setup Guide (Local Development)

### Prerequisites
- Docker & Docker Compose
- Node.js & NPM (If running without Docker)
- Composer (If running without Docker)

### Installation Steps (with Docker)

1. **Clone the repository:**
   ```bash
   git clone https://github.com/dev-talha/zakat-management.git
   cd zakat-management
   ```

2. **Environment Setup:**
   ```bash
   cp .env.docker .env
   # The default .env.docker is pre-configured with DB_HOST=db and QUEUE_CONNECTION=redis
   ```

3. **Start Docker Containers:**
   ```bash
   docker-compose up -d --build
   ```
   *(Note: The `entrypoint.sh` inside the Docker image will automatically install Composer dependencies, NPM packages, run migrations, and seed the database if it is empty. It also fixes storage permissions!)*

4. **Access the Application:**
   - Web App: `http://localhost:8080`
   - phpMyAdmin: `http://localhost:8081`

## 🐳 Running via Portainer (Production/Staging)

The project is heavily optimized for easy deployment via Portainer:
1. Go to **Stacks > Add Stack**.
2. Select **Repository** and enter this GitHub URL.
3. Keep the compose path as `docker-compose.yml`.
4. Deploy! The custom `entrypoint.sh` will handle permissions, composer install, npm build, migrations, and database seeding automatically on the first run.

## 🧪 Quick-Test Accounts

To make testing easier, the login page features a **Quick-Test Accounts** section. Clicking any role will auto-fill the credentials.
*(This feature can be toggled on/off by the Super Admin in `Settings > General Settings`).*

- **Super Admin**: `admin@czm.bd` / `password`
- **Zakat Officer**: `officer@czm.bd` / `password`
- **Donor**: `donor@czm.bd` / `password`
- **Organization**: `org@czm.bd` / `password`
- **Volunteer**: `volunteer@czm.bd` / `password`
- **Beneficiary**: `beneficiary@czm.bd` / `password`

## 🤝 Contribution Guidelines

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
