
# Cloud Token Middleware (Slim 4)

## Overview

This project implements a **PSR-15 compliant PHP middleware** for a `POST /items` endpoint using **Slim Framework v4**.

The middleware validates a custom **Cloud Access Token** passed via HTTP headers.
The token is **not a JWT** and follows **custom validation rules**.

This project demonstrates:

* Middleware-based request validation
* Clean PHP architecture
* Deterministic unit testing
* Time-based security logic

---

## Endpoint Protected

**POST /items**

Only this endpoint is protected by the middleware.

---

## Cloud Access Token Rules

A request is authorized **only if all rules pass**:

### 1. Token Length

* Must be **exactly 24 characters**

### 2. Sum Rule

* Token uses **zero-based indexing**
* `token[4]` must equal the numeric sum of:

  ```
  token[0] + token[1]
  ```
* Characters at positions `0`, `1`, and `4` must be digits

### 3. Time-Based Rule

* Token must contain the **server’s current hour**
* Hour is calculated using:

  * 24-hour format
  * Converted to **hexadecimal**
  * Case-insensitive match

**Example:**

```
Hour = 17
Hex  = 11
→ token must contain "11"
```

---

## Request Header Format

```http
Cloud-Access-Token: <24-character-token>
```

---

## Error Handling

### Invalid or Missing Token

**HTTP 401 Unauthorized**

**JSON response:**

```json
{
  "error": "Invalid Cloud Access Token"
}
```

### Valid Token

**HTTP 200 OK**

**JSON response:**

```json
{
  "status": "ok"
}
```

---

## Project Structure

```
cloud-token-middleware/
├── public/
│   └── index.php
├── src/
│   ├── Middleware/
│   │   └── CloudTokenMiddleware.php
│   └── Utils/
│       └── TokenValidator.php
├── tests/
│   └── TokenValidatorTest.php
├── composer.json
├── composer.lock
├── phpunit.xml
└── README.md
```

---

## Installation

### Requirements

* PHP 8.1+
* Composer

### Install Dependencies

```bash
php composer.phar install
```

---

## Run Tests

```bash
php vendor/phpunit/phpunit/phpunit
```

**Expected output:**

```
OK (4 tests, X assertions)
```

Tests inject `DateTime` objects to avoid reliance on system time.

---

## Run Local Server

```bash
php -S localhost:8080 -t public
```

---

## Example Requests

### Invalid Token

```bash
curl -X POST http://localhost:8080/items
```

**Response:**

```json
{"error":"Invalid Cloud Access Token"}
```

### Valid Token Example

*(Assuming current hour hex = `11`)*

```bash
curl -X POST http://localhost:8080/items \
  -H "Cloud-Access-Token: 12ab31112345678901234567"
```

**Response:**

```json
{"status":"ok"}
```

---

## Design Notes

* Middleware follows **PSR-15**
* Validation logic isolated in `TokenValidator`
* No JWTs or external auth libraries used
* Time-based logic is testable and deterministic
* Middleware is applied only at route level

---

## Author

**Omkar Biradar**

---


