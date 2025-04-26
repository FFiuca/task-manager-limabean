# Task Manager API

This is a Laravel-based Task Manager API that provides endpoints for managing tasks, epics, and task comments. It includes features like authentication, database migrations, seeding, and testing.

## Features

- User authentication (register and login)
- CRUD operations for tasks, epics, and task comments
- Soft deletes for tasks and comments
- API documentation using Swagger
- Database migrations and seeding
- Automated testing with PHPUnit

---

## Prerequisites

Ensure you have the following installed:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## Setup Instructions

1. **Clone the Repository**:
   ```bash
   git clone <repository-url>
   cd task-manager
   ```

2. **Build and Start the Containers**:
   Use Docker Compose to build and start the containers:
   ```bash
   docker-compose up --build
   ```

3. **Run Database Migrations and Seeding**:
   The `prepare_app` service will automatically run migrations and seed the database when the containers are started.

4. **Access the Application**:
   - API Base URL: [http://localhost:8000](http://localhost:8000)
   - Swagger Documentation: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

---

## Running Tests

1. **Run Tests**:
   The `migrate_and_test` service will automatically run tests after migrations and seeding. To manually run tests:
   ```bash
   docker-compose run --rm migrate_and_test
   ```

2. **View Test Results**:
   Check the logs of the `migrate_and_test` container:
   ```bash
   docker logs laravel_migrate_and_test
   ```

---

## Project Structure

- **`app/`**: Contains the application logic (models, controllers, etc.)
- **`config/`**: Configuration files for the application
- **`database/`**: Migrations, seeders, and factories
- **`routes/`**: API and web routes
- **`tests/`**: Feature and unit tests
- **`docker/`**: Docker-related configuration files

---

## Key Endpoints

### Authentication
- `POST /api/auth/register`: Register a new user
- `POST /api/auth/login`: Login a user

### Epics
- `GET /api/epic`: List all epics
- `POST /api/epic`: Create a new epic
- `PUT /api/epic/{id}`: Update an epic
- `DELETE /api/epic/{id}`: Delete an epic

### Tasks
- `GET /api/task`: List all tasks
- `POST /api/task`: Create a new task
- `PUT /api/task/{id}`: Update a task
- `DELETE /api/task/{id}`: Delete a task

### Task Comments
- `POST /api/task/comment`: Add a comment to a task
- `PUT /api/task/comment/{id}`: Update a comment
- `DELETE /api/task/comment/{id}`: Delete a comment

---

## Notes

- Ensure the `mysql` and `mysql-testing` services are running for the application and tests to work properly.
- Swagger documentation is automatically generated and available at `/api/documentation`.

