# Acme Widget Co Sales System

## Overview

Acme Widget Co has contracted the development of a proof-of-concept sales system for their widgets. This system features a basket that calculates the total cost of orders based on product pricing, delivery charges, and special offers.

## Task Summary

The task involves implementing a PHP-based basket system with the following features:

1. **Product Catalog**:
    - **Red Widget (R01)**: $32.95
    - **Green Widget (G01)**: $24.95
    - **Blue Widget (B01)**: $7.95

2. **Delivery Charges**:
    - Orders under $50: $4.95
    - Orders under $90: $2.95
    - Orders of $90 or more: Free delivery

3. **Special Offers**:
    - Buy one Red Widget, get the second one at half price

## Implementation

### Technologies Used

- **[Laravel 10](https://laravel.com/docs/10.x)**: PHP framework used for building the application.
- **Docker**: Containerization platform for consistent development and testing environments.
- **PHPUnit**: Framework for running unit and integration tests.
- **PHPStan**: Static analysis tool for PHP code quality.
- **Composer**: Dependency management tool for PHP.

### Setup and Running the Project

1. **Setup Environment**:
    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```

2. **Build and Start Docker Containers**:
    - Execute the following command to build and start the Docker containers:
      ```bash
      docker compose -f docker/docker-compose.yml up --build
      ```

3. **Access the Application**:
    - Open your browser and navigate to `http://localhost:9000`.

### Running Tests and Analysis

1. **Access the Container**:
    - Open a bash shell inside the running container:
      ```bash
      docker exec -it thrive-cart-task bash
      ```

2. **Run PHPUnit Tests**:
    - Execute the PHPUnit tests:
      ```bash
      php artisan test
      ```

3. **Run PHPStan Analysis**:
    - Execute static code analysis with PHPStan:
      ```bash
      vendor/bin/phpstan analyse
      ```

### API Endpoints

- **Postman Collection**: You can test the API endpoints using the Postman collection available [here](https://www.postman.com/crimson-sunset-8117/workspace/thrivecart/collection/5140236-2db3598d-5a14-4ff1-bc5b-29e7fe116986?action=share&creator=5140236&active-environment=5140236-d2d1d250-1d9a-48cf-85b4-40ea98f41aa8).
- **Endpoint**: `POST {{base_url}}/api/v1/checkout`
    - The Postman collection includes example cases, validation scenarios, and headers (Accept: `application/json`).
