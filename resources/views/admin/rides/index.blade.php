<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Sharing Admin - Rides</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Ride Management</h1>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Rides</h5>
                <button class="btn btn-primary btn-sm" onclick="fetchRides()">Refresh</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Passenger</th>
                                <th>Driver</th>
                                <th>Pickup Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rides-table-body">
                            <!-- Rides will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fetch rides from API
        async function fetchRides() {
            try {
                const response = await fetch('/api/admin/rides');
                const data = await response.json();

                if (data.success) {
                    displayRides(data.data);
                } else {
                    alert('Error fetching rides: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error fetching rides');
            }
        }

        // Display rides in table
        function displayRides(rides) {
            const tbody = document.getElementById('rides-table-body');
            tbody.innerHTML = '';

            if (rides.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">No rides found</td></tr>';
                return;
            }

            rides.forEach(ride => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${ride.id}</td>
                    <td>${ride.passenger ? ride.passenger.name : 'N/A'}</td>
                    <td>${ride.driver ? ride.driver.name : 'N/A'}</td>
                    <td>${ride.pickup_location}</td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(ride.status)}">${ride.status}</span>
                    </td>
                    <td>
                        <a href="/admin/rides/${ride.id}" class="btn btn-sm btn-info">View Details</a>
                    </td>
                `;
                tbody.appendChild(row);
            });
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

        // Load rides on page load
        document.addEventListener('DOMContentLoaded', fetchRides);
    </script>
</body>
</html>
