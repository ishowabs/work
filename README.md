# Fullstack Dockerized Project

## Structure

```
project/
├── frontend/   # Nuxt (Vue) app
├── backend/    # Laravel (or Flask) app
├── docker-compose.yml
└── README.md
```

## Usage

1. **Copy .env files**
   - `cp backend/.env.example backend/.env`
   - `cp frontend/.env.example frontend/.env`
   - Fill in secrets (e.g. `APP_KEY`, `JWT_SECRET`) as needed.

2. **Build and run all services:**
   ```sh
   docker-compose up --build
   ```

3. **Access your app:**
   - Frontend: [http://localhost:3000](http://localhost:3000)
   - Backend API: [http://localhost:8000](http://localhost:8000)
   - Websocket: [http://localhost:3001](http://localhost:3001)

4. **Database:**
   - MySQL is available on port 3306 (see `docker-compose.yml`)

## Notes
- Update `.env` files for production as needed.
- For Laravel, generate `APP_KEY` with `php artisan key:generate`.
- For JWT, generate `JWT_SECRET` with `php artisan jwt:secret`.
