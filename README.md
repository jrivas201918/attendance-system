# Student Attendance System (Laravel)

## 📌 Project Overview
A simple Student Attendance Management System built with Laravel. This application allows administrators to manage student records, track daily attendance, view attendance summaries, and export reports.

## 🎯 Features
*   Student management (CRUD)
*   Attendance tracking (per date and student)
*   Attendance summary report
*   Filtering by date & month
*   Export attendance records to CSV
*   Responsive & modern UI built with Tailwind CSS

## 🛠 Tech Stack

| Category | Technology |
| :--- | :--- |
| **Backend** | Laravel, PHP |
| **Frontend** | Blade, Tailwind CSS, JavaScript |
| **Database** | MySQL (or other supported RDBMS) |
| **DevOps** | Vite, Composer, NPM |

## 🖼 Screenshots
*(You can add screenshots here by dragging and dropping them into the GitHub file editor.)*

## 🚀 Getting Started (Local Setup)

1️⃣ **Clone the repository:**
```bash
git clone https://github.com/jrivas201918/attendance-system.git
```

2️⃣ **Navigate to project folder:**
```bash
cd attendance-system
```

3️⃣ **Install dependencies:**
```bash
composer install
npm install
```

4️⃣ **Set up environment file:**
```bash
cp .env.example .env
```
Next, open the `.env` file and set up your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

5️⃣ **Generate application key:**
```bash
php artisan key:generate
```

6️⃣ **Run migrations:**
```bash
php artisan migrate
```
This will create the necessary tables in your database.

7️⃣ **Run the development servers:**
In your first terminal, run the Vite development server:
```bash
npm run dev
```
In a second terminal, run the PHP development server:
```bash
php artisan serve
```

The application will now be available at `http://127.0.0.1:8000`.

## 📊 Future Improvements
- [ ] **Role-based Access Control:** Differentiate between Admin and Teacher roles.
- [ ] **Enhanced Authentication:** Add features like socialite login or two-factor authentication.
- [ ] **Email Notifications:** Automatically send attendance reports via email.
- [ ] **Advanced Analytics:** A dedicated dashboard with charts and more detailed insights.
- [ ] **Deployment:** Set up a deployment pipeline to a cloud platform.

## 👨‍💻 Author
**Joshua Tegio Rivas**

*   **GitHub:** [jrivas201918](https://github.com/jrivas201918)
*   **LinkedIn:** (Add your LinkedIn profile link here)
