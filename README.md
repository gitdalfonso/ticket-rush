# ⚡ TicketRush

TicketRush is a high-performance ticket sales platform built with Laravel. It is specifically designed to handle high-concurrency scenarios (such as ticket sales for highly demanded concerts), guaranteeing minimal response times and completely preventing inventory overselling.



## 🚀 Key Features

### 🛡️ Backend & Architecture (Senior Level)
* **Atomic Locks:** Integration with **Redis** to lock transactions in milliseconds. If 1,000 users attempt to buy the last ticket simultaneously, the system will only process one order, rejecting the rest without crashing the database.
* **Asynchronous Processing (Queues):** Email delivery with tickets (including QR generation) is delegated to **Redis Workers** in the background. This allows the user to see the "Successful Purchase" screen instantly.
* **Real Inventory Management:** Instead of simply subtracting a number from a capacity column, the system generates "Physical Seats" (database rows) and updates their status in real-time.
* **Smart Admin Panel:** Built with **FilamentPHP**. It includes custom Hooks (`afterSave`) that detect if an administrator increases an event's capacity and automatically generate the missing tickets in the database.

### 🎨 Frontend & User Experience (UI/UX)
* **Modern and Responsive Design:** Built entirely with **Tailwind CSS**.
* **DRY Templates:** Use of anonymous Blade components (`<x-layout>`) for easy and maintainable layout structure (Navbar, Footer).
* **SEO-Friendly URLs:** Implementation of dynamic Slugs (`/concerts/metallica-tour`) instead of database IDs, improving SEO and URL readability.
* **Dynamic QR Code Generation:** Tickets generate scannable QRs in SVG format with adjusted backgrounds to ensure perfect contrast and readability under any lighting condition.
* **Real-Time Availability:** Visual progress bars that calculate and display the remaining inventory by dynamically subtracting sold tickets from the total capacity.

## 🛠️ Tech Stack

* **Framework:** Laravel 11
* **Database:** MySQL / SQLite
* **Cache & Queues:** Redis
* **Admin Panel:** FilamentPHP v3
* **Styling:** Tailwind CSS
* **Authentication:** Laravel Breeze (Customized to fit the business flow)
* **QR Generation:** `simplesoftwareio/simple-qrcode`

## 🧠 Purchase Flow (Under the Hood)

1. The user clicks "Buy Ticket".
2. Laravel requests an **Atomic Lock from Redis** for that specific concert.
3. If the lock is acquired, it queries the database for a ticket where `status = 'available'`.
4. The ticket is assigned to the user, its status changes to `sold`, and an `Order` is created.
5. The Redis Lock is released.
6. A Job is dispatched to the **Queue** to generate the QR code and send the confirmation email.
7. The user is instantly redirected to their dashboard.

## ⚙️ Local Installation

Follow these steps to set up the project in your local environment. Ensure you have PHP 8.2+, Composer, Node.js, and a running **Redis** server.

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/your-username/ticketrush.git](https://github.com/your-username/ticketrush.git)
   cd ticketrush
   
2. **Install dependencies:**
   ```bash
   composer install
   npm install && npm run build
   
3. **Configure environment:**
    ```bash
   cp .env.example .env
   php artisan key:generate
   
4. **Set up the database and Redis:**
   Open your .env file and make sure Redis is set as your default driver for cache and queues:
    ```bash
   DB_CONNECTION=sqlite # or mysql
   CACHE_STORE=redis
   QUEUE_CONNECTION=redis

5. **Run migrations:**
   ```bash
   php artisan migrate
   
6. **Start the application:**
   ```bash
   # Terminal 1: Start the local development server
   php artisan serve
   # Terminal 2: Start the queue worker to process emails and background tasks
   php artisan queue:work

## AI Usage
Some structural scaffolding and initial code drafts were AI-assisted. All generated code was manually reviewed, refactored, and adjusted to ensure:

* Compliance with SOLID principles.

* Security best practices (e.g., atomic locks to prevent race conditions).

* Proper validation and error handling.

* A maintainable and scalable architecture.
