# Laravel Ride-Sharing

A complete Laravel backend implementation for a ride-sharing. This project demonstrates clean API design, proper business logic implementation, and a functional admin panel without any frontend frameworks.

## 🚀 Project Overview

This is a **backend-only** Laravel application. The system handles ride requests, driver management, and ride completion logic with a simple yet robust architecture.

**Key Assessment Features:**
- ✅ Passenger ride creation and management
- ✅ Driver location tracking and ride requests
- ✅ Dual completion logic (both passenger and driver must mark complete)
- ✅ Admin panel with real-time data
- ✅ Clean RESTful API design
- ✅ No authentication system (IDs passed explicitly)

## 🛠 Tech Stack

- **Laravel 12** - PHP Framework
- **PHP 8.2.12** - Runtime
- **MySQL** - Database
- **Bootstrap 5** - Admin panel styling
- **Vanilla JavaScript** - Admin panel interactivity
- **Laravel Eloquent** - ORM

## 📦 Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL
- Apache/Nginx

### Step-by-Step Setup

1. **Clone the repository**
```bash
git clone https://github.com/mrkumarvijay/ride-sharing.git
cd ride-sharing
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
```

6. **Generate application key**
```bash
php artisan key:generate
```

7. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

8. **Start the development server**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 🗄 Database Seeding

The application includes seeders for testing:

```bash
# Run all seeders
php artisan db:seed

# Run specific seeders
php artisan db:seed --class=PassengerSeeder
php artisan db:seed --class=DriverSeeder
```

**Default Test Data:**
- **Passengers**: 5 test passengers with names and emails
- **Drivers**: 5 test drivers with names, emails, and random locations

## 🌐 API Documentation

### Passenger APIs

#### Create Ride Request
- **Endpoint**: `POST /api/passenger/create-ride`
- **Description**: Creates a new ride request
- **Request Body**:
```json
{
    "passenger_id": 1,
    "pickup_location": "Central Station",
    "dropoff_location": "Airport",
    "pickup_latitude": 40.7128,
    "pickup_longitude": -74.0060
}
```
- **Success Response**:
```json
{
    "success": true,
    "message": "Ride created successfully",
    "data": {
        "id": 1,
        "passenger_id": 1,
        "driver_id": null,
        "pickup_location": "Central Station",
        "dropoff_location": "Airport",
        "pickup_latitude": "40.7128",
        "pickup_longitude": "-74.0060",
        "dropoff_latitude": null,
        "dropoff_longitude": null,
        "status": "requested",
        "passenger_completed": false,
        "driver_completed": false,
        "created_at": "2026-01-29T06:30:00.000000Z",
        "updated_at": "2026-01-29T06:30:00.000000Z"
    }
}
```

#### Approve Driver
- **Endpoint**: `POST /api/passenger/approve-driver`
- **Description**: Approves a driver for a ride
- **Request Body**:
```json
{
    "passenger_id": 1,
    "ride_id": 1,
    "driver_id": 2
}
```
- **Success Response**:
```json
{
    "success": true,
    "message": "Driver approved successfully",
    "data": {
        "id": 1,
        "passenger_id": 1,
        "driver_id": 2,
        "pickup_location": "Central Station",
        "dropoff_location": "Airport",
        "status": "accepted",
        "is_available": false
    }
}
```

#### Mark Ride Completed (Passenger)
- **Endpoint**: `POST /api/passenger/mark-completed`
- **Description**: Marks ride as completed by passenger
- **Request Body**:
```json
{
    "passenger_id": 1,
    "ride_id": 1
}
```
- **Success Response**:
```json
{
    "success": true,
    "message": "Ride marked as completed by passenger",
    "data": {
        "id": 1,
        "passenger_completed": true,
        "driver_completed": false,
        "status": "requested"
    }
}
```

### Driver APIs

#### Update Location
- **Endpoint**: `POST /api/driver/update-location`
- **Description**: Updates driver's current GPS coordinates
- **Request Body**:
```json
{
    "driver_id": 1,
    "latitude": 40.7128,
    "longitude": -74.0060
}
```
- **Success Response**:
```json
{
    "success": true,
    "message": "Location updated successfully",
    "data": {
        "id": 1,
        "name": "Driver Name",
        "latitude": "40.7128",
        "longitude": "-74.0060",
        "is_available": true
    }
}
```

#### Get Nearby Rides
- **Endpoint**: `GET /api/driver/nearby-rides`
- **Description**: Fetches pending rides within specified radius
- **Query Parameters**:
  - `driver_id`: Driver ID
  - `latitude`: Current latitude
  - `longitude`: Current longitude
  - `radius`: Search radius in kilometers (0.1-50)

- **Example Request**: `GET /api/driver/nearby-rides?driver_id=1&latitude=40.7128&longitude=-74.0060&radius=5`

- **Success Response**:
```json
{
    "success": true,
    "message": "Nearby rides fetched successfully",
    "data": [
        {
            "id": 1,
            "passenger_id": 1,
            "pickup_location": "Central Station",
            "pickup_latitude": "40.7128",
            "pickup_longitude": "-74.0060",
            "status": "requested"
        }
    ]
}
```

#### Request Ride
- **Endpoint**: `POST /api/driver/request-ride`
- **Description**: Driver requests/claims a ride
- **Request Body**:
```json
{
    "driver_id": 1,
    "ride_id": 1
}
```
- **Success Response**:
```json
{
    "success": true,
    "message": "Ride accepted successfully",
    "data": {
        "id": 1,
        "driver_id": 1,
        "status": "accepted",
        "is_available": false
    }
}
```

#### Mark Ride Completed (Driver)
- **Endpoint**: `POST /api/driver/mark-completed`
- **Description**: Marks ride as completed by driver
- **Request Body**:
```json
{
    "driver_id": 1,
    "ride_id": 1
}
```
- **Success Response**:
```json
{
    "success": true,
    "message": "Ride marked as completed by driver",
    "data": {
        "id": 1,
        "passenger_completed": false,
        "driver_completed": true,
        "status": "requested"
    }
}
```

### Admin APIs

#### Get All Rides
- **Endpoint**: `GET /api/admin/rides`
- **Description**: Returns all rides with passenger and driver details
- **Success Response**:
```json
{
    "success": true,
    "message": "Rides fetched successfully",
    "data": [
        {
            "id": 1,
            "passenger_id": 1,
            "driver_id": 2,
            "pickup_location": "Central Station",
            "status": "accepted",
            "passenger": {
                "id": 1,
                "name": "Passenger Name",
                "email": "passenger@example.com"
            },
            "driver": {
                "id": 2,
                "name": "Driver Name",
                "email": "driver@example.com"
            }
        }
    ]
}
```

#### Get Ride Details
- **Endpoint**: `GET /api/admin/rides/{id}`
- **Description**: Returns detailed information about a specific ride
- **Success Response**:
```json
{
    "success": true,
    "message": "Ride details fetched successfully",
    "data": {
        "id": 1,
        "passenger_id": 1,
        "driver_id": 2,
        "pickup_location": "Central Station",
        "dropoff_location": "Airport",
        "pickup_latitude": "40.7128",
        "pickup_longitude": "-74.0060",
        "status": "accepted",
        "passenger_completed": false,
        "driver_completed": false,
        "passenger": {
            "id": 1,
            "name": "Passenger Name",
            "email": "passenger@example.com",
            "phone": null
        },
        "driver": {
            "id": 2,
            "name": "Driver Name",
            "email": "driver@example.com",
            "phone": null,
            "latitude": "40.7128",
            "longitude": "-74.0060",
            "is_available": false
        }
    }
}
```

## 👥 Admin Panel

### Access
- **URL**: `http://localhost:8000/admin/rides`
- **Technology**: Blade templates with Bootstrap 5
- **Features**: Real-time data via JavaScript API calls

### Admin Panel Features
- ✅ View all rides in a responsive table
- ✅ See passenger and driver information
- ✅ View ride status with color-coded badges
- ✅ Access detailed ride information
- ✅ Refresh data with one click
- ✅ No authentication required

### Admin Panel Screenshots

**Ride List View:**
- Shows all rides with status badges
- Displays passenger and driver names
- Includes action buttons for details

**Ride Details View:**
- Complete ride information
- Passenger and driver contact details
- GPS coordinates display
- Completion status indicators

## 🧪 Testing APIs

### Using cURL

```bash
# Create a ride
curl -X POST http://localhost:8000/api/passenger/create-ride \
  -H "Content-Type: application/json" \
  -d '{
    "passenger_id": 1,
    "pickup_location": "Central Station",
    "dropoff_location": "Airport",
    "pickup_latitude": 40.7128,
    "pickup_longitude": -74.0060
  }'

# Get all rides
curl http://localhost:8000/api/admin/rides

# Update driver location
curl -X POST http://localhost:8000/api/driver/update-location \
  -H "Content-Type: application/json" \
  -d '{
    "driver_id": 1,
    "latitude": 40.7128,
    "longitude": -74.0060
  }'
```

### Using Postman

1. **Import Collection**: Create a new collection for "Ride Sharing API"
2. **Set Base URL**: `http://localhost:8000/api`
3. **Test Endpoints**: Use the examples above as Postman requests
4. **Environment Variables**: Set `BASE_URL` to `http://localhost:8000`

### Test Workflow

1. **Seed Database**: `php artisan db:seed`
2. **Create Ride**: Use passenger API to create a ride
3. **Update Driver Location**: Update driver coordinates
4. **Find Nearby Rides**: Driver searches for rides
5. **Request Ride**: Driver claims the ride
6. **Approve Driver**: Passenger approves the driver
7. **Complete Ride**: Both passenger and driver mark complete

## 🎯 Business Logic

### Ride Lifecycle

1. **Ride Creation**: Passenger creates ride with status "requested"
2. **Driver Discovery**: Drivers search for nearby pending rides
3. **Ride Request**: Driver requests/claims a ride
4. **Driver Approval**: Passenger approves the assigned driver
5. **Ride Completion**: Both parties must mark complete for final status

### Key Rules

- **Dual Completion**: Ride is only "completed" when both passenger and driver mark complete
- **Driver Availability**: Drivers become unavailable when accepting rides
- **Location Tracking**: Drivers can update their GPS coordinates
- **Radius Search**: Nearby rides calculated using Haversine formula
- **Status Transitions**: requested → accepted → completed

### Data Validation

- All API endpoints include proper validation
- Required fields are enforced
- Numeric coordinates are validated
- Existing IDs are verified
- Business rules are enforced (e.g., cannot complete non-existent rides)

## 🔧 Troubleshooting

### Common Issues

**Database Connection Error**
```bash
# Check .env database settings
# Ensure MySQL is running
# Verify database exists
```

**Migration Errors**
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Re-run migrations
php artisan migrate:fresh --seed
```

**CORS Issues**
- The application includes CORS headers in responses
- No additional configuration needed for local development

**API Not Found (404)**
- Ensure `php artisan serve` is running
- Check URL paths match documentation
- Verify routes are defined in `routes/api.php`

### Development Tips

**Clear Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**Check Routes**
```bash
php artisan route:list
```

**Database Debugging**
```bash
# Check database content
php artisan tinker
>>> \App\Models\Ride::all();
```
---
