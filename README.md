# Technical Test Backend: Todo List API
This backend technical test solution, built with Laravel.

## Local Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/munaky/todo-list
   cd todo-list
```

2. **Copy the environment file**

   ```bash
   cp .env.example .env
   ```

3. **Configure the database**

   Open `.env` and update the database settings:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Install Composer dependencies**

   ```bash
   composer install
   ```

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Run the development server**

   ```bash
   php artisan serve
   ```

   Access the project at: [http://localhost:8000](http://localhost:8000)

---

## Running with Docker

If you prefer using Docker, make sure Docker and Docker Compose are installed.

1. **Start the containers**

   ```bash
   docker-compose up -d
   ```

2. **Access the project**

   Open your browser and go to: [http://localhost:8000](http://localhost:8000)

```
