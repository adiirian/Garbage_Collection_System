# Garbage Collection System

This project is a garbage collection system designed for Trinidad, Bohol, Philippines. It provides functionalities for monitoring garbage bin levels and managing alerts from public users regarding bin conditions.

## Features

- **Admin Dashboard**: An admin interface to monitor and manage the overall system.
- **User Roles**: 
  - **Admin**: Manages the system and oversees operations.
  - **Collector**: Responsible for collecting garbage and managing bin statuses.
  - **Public User**: Can send alerts about specific bins.
- **Bin Monitoring**: Tracks the status of garbage bins, detecting if they are full, empty, or overflowing.
- **Alerts**: Public users can send alerts regarding specific bins, which will notify collectors.

## Database

The project uses a MySQL database named `garbage_collection_system`. The following tables are created:

- **users**: Stores user information and roles.
- **roles**: Defines user roles (admin, collector, public user).
- **bins**: Contains information about garbage bins, including their status and location.
- **alerts**: Records alerts sent by public users regarding bin conditions.

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```
   cd garbage-collection-system
   ```

3. Install dependencies:
   ```
   composer install
   ```

4. Set up the environment file:
   ```
   cp .env.example .env
   ```

5. Configure your database settings in the `.env` file.

6. Run migrations to create the database tables:
   ```
   php artisan migrate
   ```

7. Seed the database with initial data (optional):
   ```
   php artisan db:seed
   ```

## Usage

- Start the Laravel development server:
  ```
  php artisan serve
  ```

- Access the admin dashboard and other functionalities through the defined routes.

## Testing

The project includes feature and unit tests to ensure the functionality of the garbage collection system. Run the tests using:
```
php artisan test
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is open-source and available under the MIT License.