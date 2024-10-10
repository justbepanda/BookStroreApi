# Bookstore Service

## Description

The Bookstore Service is developed using PHP 8.2 and the Laravel 11 framework. It provides a RESTful API for managing data about books and authors, allowing users to:

- **Create and edit authors**: Includes validation for fields such as name, information, and date of birth.
- **Retrieve a list of authors with pagination**: Sorting by the number of added books and displaying information about each author.
- **Get information about a specific author**: Includes a list of added books.
- **Create and edit books**: Validation for fields such as author ID, title, annotation, and publication date.
- **Retrieve a list of books with pagination**: Each book includes information about the author.
- **Get information about a specific book**: Includes information about the author.

## Additional Functionality

The project includes functionality for a table of contents with the text of each chapter and character count for the text of all chapters. After adding or editing a chapter, the character count is automatically updated.

## Installation

Start the service using Docker
```bash
docker-compose up
```

## Environment Configuration

Copy the example environment file and rename it to `.env`:

```bash
cp .env.example .env
