<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, #007bff, #6610f2);
        }

        .container {
            margin-top: 40px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, height 0.3s ease-in-out;
            height: 120px; /* Adjust as needed */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
        }

        .card.expanded {
            height: auto !important; /* Allow the card to expand based on content */
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h5 {
            font-weight: bold;
            color: #333;
        }

        .toggle-btn {
            width: 100%;
            text-align: center;
        }

        .form-container {
            display: none;
            width: 100%;
            padding: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="#">Resident Dashboard</a>
        <a class="btn btn-outline-light" href="index.php">Logout</a>
    </div>
</nav>

    
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center p-3" id="complaintCard">
                    <h5>File a Complaint</h5>
                    <button class="btn btn-primary toggle-btn" onclick="toggleCard('complaintCard', 'complaintForm')">Expand</button>
                    <div class="form-container" id="complaintForm">
                        <form action="complaints.php" method="POST">
                            <input type="hidden" name="submit_complaint" value="1">
                            <div class="mb-3">
                                <label class="form-label">Resident Name</label>
                                <input type="text" class="form-control" name="resident_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Complaint Details</label>
                                <textarea class="form-control" name="details" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Complaint</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="appointmentCard">
                    <h5>Book an Appointment</h5>
                    <button class="btn btn-primary toggle-btn" onclick="toggleCard('appointmentCard', 'appointmentForm')">Expand</button>
                    <div class="form-container" id="appointmentForm">
                        <form action="appointments.php" method="POST">
                            <input type="hidden" name="schedule_appointment" value="1">
                            <div class="mb-3">
                                <label class="form-label">Resident ID</label>
                                <input type="text" class="form-control" name="resident_id" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Appointment Date</label>
                                <input type="date" class="form-control" name="appointment_date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Purpose</label>
                                <textarea class="form-control" name="purpose" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Schedule Appointment</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="businessCard">
                    <h5>Register a Business</h5>
                    <button class="btn btn-primary toggle-btn" onclick="toggleCard('businessCard', 'businessForm')">Expand</button>
                    <div class="form-container" id="businessForm">
                        <form action="businesses.php" method="POST">
                            <input type="hidden" name="register_business" value="1">
                            <div class="mb-3">
                                <label class="form-label">Business Name</label>
                                <input type="text" class="form-control" name="business_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Owner Name</label>
                                <input type="text" class="form-control" name="owner_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" name="contact" required maxlength="11">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register Business</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="announcementCard">
                    <h5>View Barangay Announcements</h5>
                    <button type="submit" class="btn btn-primary toggle-btn w-100" onclick="window.location.href='announcements.php'">
                        View Announcements
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="directoryCard">
                    <h5>Check Online Directory</h5>
                    <button type="submit" class="btn btn-primary toggle-btn w-100" onclick="window.location.href='directory.php'">
                        View Directory
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center p-3" id="statusCard">
                    <h5>Track Request/Complaint Status</h5>
                    <button class="btn btn-primary toggle-btn" onclick="toggleCard('statusCard', 'statusForm')">Expand</button>
                    <div class="form-container" id="statusForm">
                        <form action="track_status.php" method="GET">
                            <div class="mb-3">
                                <label class="form-label">Tracking ID</label>
                                <input type="text" class="form-control" name="tracking_id" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Track Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCard(cardId, formId) {
            const card = document.getElementById(cardId);
            const form = document.getElementById(formId);
            const button = card.querySelector('.toggle-btn');

            if (card.classList.contains('expanded')) {
                card.classList.remove('expanded');
                form.style.display = "none";
                button.innerText = "Expand";
            } else {
                card.classList.add('expanded');
                form.style.display = "block";
                button.innerText = "Collapse";
            }
        }

        document.querySelector('form[action="appointments.php"]').addEventListener('submit', function(event) {
            const appointmentDate = new Date(document.querySelector('input[name="appointment_date"]').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set to start of the day

            if (appointmentDate < today) {
                event.preventDefault();
                alert("Cannot set appointment date in the past.");
            }
        });

        document.querySelector('form[action="businesses.php"]').addEventListener('submit', function(event) {
            const contactNumber = document.querySelector('input[name="contact"]').value;
            const contactNumberPattern = /^09\d{9}$/;

            if (!contactNumberPattern.test(contactNumber)) {
                event.preventDefault();
                alert("Contact number must be exactly 11 digits and follow the format 09xxxxxxxxx.");
            }
        });
    </script>
</body>
</html>
