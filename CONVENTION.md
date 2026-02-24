# Coding Conventions - Reservation Cinema PHP B2

## Naming Conventions

### Files
- **snake_case** for all files
- Controllers: `{feature}Controller.php` → `homeController.php`, `bookingController.php`
- Models: `{feature}Model.php` → `homeModel.php`, `reservationModel.php`
- Views: `{feature}.php` → `home.php`, `login.php`
- Config: `database.php`, `router.php`

### Variables
- **snake_case** for variables: `$user_name`, `$total_price`, `$is_logged_in`
- **ALL_CAPS** for constants: `DB_HOST`, `MAX_SEATS_PER_BOOKING`, `API_KEY`
- Array keys in snake_case: `['user_id' => 1, 'email_address' => 'test@mail.com']`

### Functions & Methods
- **snake_case** for functions: `get_user_by_id()`, `create_booking()`, `is_seat_available()`
- Common prefixes: `get_`, `create_`, `update_`, `delete_`, `is_`, `has_`, `validate_`

### Classes
- **PascalCase** for class names: `UserController`, `BookingModel`, `DatabaseConnection`
- Methods inside classes use **snake_case**: `get_all_bookings()`, `create_new_booking()`

### Database
- Tables: **snake_case, plural** → `users`, `bookings`, `movies`, `reservations`
- Columns: **snake_case** → `user_id`, `booking_date`, `created_at`, `updated_at`
- Primary key: `id`
- Foreign keys: `{table_name}_id` → `user_id`, `movie_id`

---

## Documentation

### When to Document
- **Complex functions**: Add PHPDoc with params, return types, and description
- **Self-explanatory functions**: Documentation optional
- **Classes**: Brief description of purpose
- **Complex logic**: Inline comments only when necessary

```php
/**
 * Creates a new booking for a movie session
 * @param int $user_id User ID
 * @param int $movie_id Movie ID
 * @param array $seat_numbers Seats to book
 * @return array Booking details
 */
function create_movie_booking($user_id, $movie_id, $seat_numbers) {
    // ...
}

// Simple function - no doc needed
function get_user_name($user_id) {
    // ...
}
```

---

## Project Structure

### MVC Pattern
```
Request → Router → Controller → Model → Database
                      ↓
                    View → Response
```

### Directory Layout
- **Controllers** (`src/controllers/`): Handle requests, call models, return views
- **Models** (`src/models/`): Database operations, business logic
- **Views** (`src/views/`): HTML presentation with minimal PHP
- **Config** (`src/config/`): Database settings, constants
- **Public** (`public/`): Entry point, CSS, JS, images

---

## PHP Best Practices

### Basic Rules
- Use `<?php` tags (no short tags)
- No closing `?>` in PHP-only files
- UTF-8 encoding without BOM
- 4 spaces for indentation

### Security Essentials
```php
// Sanitize input
$user_input = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

// Prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);

// Escape output
echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');

// Password hashing
$hashed = password_hash($password, PASSWORD_DEFAULT);
```

### Error Handling
```php
try {
    $result = create_booking($data);
} catch (Exception $e) {
    error_log($e->getMessage());
    return ['error' => 'Booking failed'];
}
```

---

## Git Conventions

### Branches
- **kebab-case**: `feature/add-booking`, `fix/login-error`, `hotfix/security-patch`

### Commits
- Imperative mood, capitalized, no period
- Good: `"Add booking validation"`, `"Fix seat availability check"`
- Bad: `"added stuff"`, `"WIP"`, `"bug fix."`

### Commit Types
- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation
- `refactor:` Code refactoring
- `style:` Formatting
- `chore:` Maintenance

---

## Quick Reference

| Element | Convention | Example |
|---------|-----------|---------|
| Files | snake_case | `booking_controller.php` |
| Variables | snake_case | `$user_name` |
| Constants | ALL_CAPS | `DB_HOST` |
| Functions | snake_case | `get_user_data()` |
| Classes | PascalCase | `BookingController` |
| Methods | snake_case | `create_booking()` |
| DB Tables | snake_case, plural | `bookings` |
| DB Columns | snake_case | `user_id` |
| Git Branches | kebab-case | `feature/add-login` |

---

**Version**: 1.0 | **Updated**: February 6, 2026
