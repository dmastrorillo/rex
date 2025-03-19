# Rex Software Coding Challenge

A Laravel application for managing contacts with both web interface and command-line capabilities.

## Purpose

This application demonstrates a simple contact management system built with Laravel, featuring:

- Web interface for managing contacts
- CLI commands for creating contacts
- Unified validation rules across interfaces
- Service-based architecture to reduce code duplication

## Requirements

- PHP 8.1 or higher
- Composer
- Laravel 10.x
- MySQL or compatible database
- Node.js and Yarn (for frontend assets)

## Installation

1. Clone the repository:

    ```
    git clone <repository-url>
    cd rex
    ```

2. Install PHP dependencies:

    ```
    composer install
    ```

3. Install frontend dependencies:

    ```
    yarn install
    yarn dev
    ```

4. Create a copy of the environment file:

    ```
    cp .env.example .env
    ```

5. Generate application key:

    ```
    php artisan key:generate
    ```

6. Configure your database in the `.env` file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=rex
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. Run migrations and seed the database:

    ```
    php artisan migrate
    php artisan db:seed --class=ContactsTableSeeder
    ```

8. Start the development server:
    ```
    composer run dev
    ```

## Usage

### Web Interface

Access the web interface by navigating to `http://localhost:8000` in your browser. Here you can:

- View a list of all contacts
- Create new contacts
- Edit existing contacts
- Delete contacts

### CLI Usage

The application includes command-line tools for managing contacts:

1.  Create a new contact:

    ```
    php artisan contacts:create
        Description:
        Create a new contact via the command line

        Usage:
        contacts:create [options]

        Options:
        --firstName[=FIRSTNAME] First name of the contact
        --surname[=SURNAME] Surname of the contact
        --email[=EMAIL] Email address of the contact
        --phone[=PHONE] Phone number of the contact (format: +61xxxxxxxxx or +64xxxxxxxx)
    ```

2.  List all contacts:

    ```

    php artisan contacts:list
        Description:
        List contacts with optional search and pagination

        Usage:
        contacts:list [options]

        Options:
        --search[=SEARCH] Optional search query to filter contacts
        --page[=PAGE] Page number for paginated results [default: "1"]

    ```

3.  Update a contact:

    ```
    php artisan contacts:update
        Description:
        Update a contact via the command line

        Usage:
        contacts:update [options]

        Options:
            --id[=ID]                ID of the contact to update
            --firstName[=FIRSTNAME]  First name of the contact
            --surname[=SURNAME]      Surname of the contact
            --email[=EMAIL]          Email address of the contact
            --phone[=PHONE]          Phone number of the contact (format: +61xxxxxxxxx or +64xxxxxxxx)
    ```

4.  Delete a contact:

    ```
    php artisan contacts:delete -h
        Description:
        Delete a contact via the command line

        Usage:
        contacts:delete [options]

        Options:
            --id[=ID]         ID of the contact to delete
    ```

5. Call a contact:

    ```
    php artisan contacts:call
        Description:
        Call a contact via the command line

        Usage:
        contacts:call [options]

        Options:
            --id[=ID]         ID of the contact to call
    ```

6. Poll a call:

    ```
    php artisan contacts:poll
        Description:
        Poll a call via the command line

        Usage:
        contacts:poll [options]

        Options:
            --id[=ID]         ID of the call to poll
    ```

## Validation Rules

When creating or updating contacts, the following validation rules apply:

- **First Name**: Required
- **Surname**: Required
- **Email**: Must be a valid email address and unique in the system
- **Phone**: Must be unique and match one of these formats:
- Australian format: +61XXXXXXXXX (12 characters total)
- New Zealand format: +64XXXXXXXX (11 characters total)
