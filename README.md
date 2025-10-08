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

6. **Run database migrations**
    ```bash
   php artisan migrate
   ```

7. **Run the development server**

   ```bash
   php artisan serve
   ```

   The server is now running, and you can test the API endpoints using Postman.

---

## Running with Docker

If you prefer using Docker, make sure Docker and Docker Compose are installed.

1. **Start the containers**

   ```bash
   docker-compose up -d
   ```

2. **Access the project**

   The application is running on **localhost** at port **8000**. You can use this URL as the `base_url` in Postman to test all API endpoints:
   http://localhost:8000

---

## Testing with Postman

For API testing, you can import the provided Postman collection:

1. Open Postman.
2. Import the `postman_collection.json` file.
3. Set the `base_url` variable in Postman to match your environment
4. Use the requests in the collection to test the API endpoints.

