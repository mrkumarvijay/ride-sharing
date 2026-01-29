<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Details - Ride Sharing Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Ride Details</h1>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ride Information</h5>
                <button class="btn btn-primary btn-sm" onclick="fetchRideDetails()">Refresh</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Ride ID:</h6>
                        <p id="ride-id">-</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Status:</h6>
                        <p id="ride-status">
                            <span class="badge bg-secondary">-</span>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Passenger:</h6>
                        <p id="passenger-name">-</p>
                        <p id="passenger-email">-</p>
                        <p id="passenger-phone">-</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Driver:</h6>
                        <p id="driver-name">-</p>
                        <p id="driver-email">-</p>
                        <p id="driver-phone">-</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Pickup Location:</h6>
                        <p id="pickup-location">-</p>
                        <p id="pickup-coords">-</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Dropoff Location:</h6>
                        <p id="dropoff-location">-</p>
                        <p id="dropoff-coords">-</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Passenger Completed:</h6>
                        <p id="passenger-completed">
                            <span class="badge bg-secondary">-</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Driver Completed:</h6>
                        <p id="driver-completed">
                            <span class="badge bg-secondary">-</span>
                        </p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <a href="/admin/rides" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Get ride ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const rideId = urlParams.get('id') || window.location.pathname.split('/').pop();

        // Fetch ride details from API
        async function fetchRideDetails() {
            try {
                const response = await fetch(`/api/admin/rides/${rideId}`);
                const data = await response.json();

                if (data.success) {
                    displayRideDetails(data.data);
                } else {
                    alert('Error fetching ride details: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error fetching ride details');
            }
        }

        // Display ride details
        function displayRideDetails(ride) {
            // Basic ride info
            document.getElementById('ride-id').textContent = ride.id;

            const statusBadge = document.querySelector('#ride-status .badge');
            statusBadge.textContent = ride.status;
            statusBadge.className = 'badge ' + getStatusBadgeClass(ride.status);

            // Passenger info
            if (ride.passenger) {
                document.getElementById('passenger-name').textContent = ride.passenger.name;
                document.getElementById('passenger-email').textContent = ride.passenger.email;
                document.getElementById('passenger-phone').textContent = ride.passenger.phone || 'N/A';
            } else {
                document.getElementById('passenger-name').textContent = 'N/A';
                document.getElementById('passenger-email').textContent = 'N/A';
                document.getElementById('passenger-phone').textContent = 'N/A';
            }

            // Driver info
            if (ride.driver) {
                document.getElementById('driver-name').textContent = ride.driver.name;
                document.getElementById('driver-email').textContent = ride.driver.email;
                document.getElementById('driver-phone').textContent = ride.driver.phone || 'N/A';
            } else {
                document.getElementById('driver-name').textContent = 'N/A';
                document.getElementById('driver-email').textContent = 'N/A';
                document.getElementById('driver-phone').textContent = 'N/A';
            }

            // Location info
            document.getElementById('pickup-location').textContent = ride.pickup_location;
            document.getElementById('pickup-coords').textContent =
                `Lat: ${ride.pickup_latitude}, Lng: ${ride.pickup_longitude}`;

            if (ride.dropoff_location) {
                document.getElementById('dropoff-location').textContent = ride.dropoff_location;
                document.getElementById('dropoff-coords').textContent =
                    `Lat: ${ride.dropoff_latitude || 'N/A'}, Lng: ${ride.dropoff_longitude || 'N/A'}`;
            } else {
                document.getElementById('dropoff-location').textContent = 'N/A';
                document.getElementById('dropoff-coords').textContent = 'N/A';
            }

            // Completion status
            const passengerCompletedBadge = document.querySelector('#passenger-completed .badge');
            passengerCompletedBadge.textContent = ride.passenger_completed ? 'Yes' : 'No';
            passengerCompletedBadge.className = 'badge ' + (ride.passenger_completed ? 'bg-success' : 'bg-danger');

            const driverCompletedBadge = document.querySelector('#driver-completed .badge');
            driverCompletedBadge.textContent = ride.driver_completed ? 'Yes' : 'No';
            driverCompletedBadge.className = 'badge ' + (ride.driver_completed ? 'bg-success' : 'bg-danger');
        }

        // Get badge class based on status
        function getStatusBadgeClass(status) {
            switch(status) {
                case 'requested': return 'bg-warning';
                case 'accepted': return 'bg-primary';
                case 'completed': return 'bg-success';
                default: return 'bg-secondary';
            }
        }

        // Load ride details on page load
        document.addEventListener('DOMContentLoaded', fetchRideDetails);
    </script>
</body>
</html>
