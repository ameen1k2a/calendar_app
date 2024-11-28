<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar App</title>
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    
    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: none;
        width: 300px; 
        padding: 15px; 
        font-size: 16px; 
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
    }
</style>
</head>

<body class="bg-light">

    <div id="alertMessage" class="alert alert-dismissible fade show" role="alert">
        <span id="alertText">This is an alert message!</span>
    </div>
    <div class="container mt-3 mb-3">
        <h1 class="text-center mb-4">Calendar App</h1>
        <div id="calendar" class="shadow p-3 bg-white rounded"></div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="eventTitle" class="form-label">Title:</label>
                        <input type="text" id="eventTitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Description:</label>
                        <textarea id="eventDescription" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="eventStartDate" class="form-label">Start Date:</label>
                        <input type="date" id="eventStartDate" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="eventEndDate" class="form-label">End Date:</label>
                        <input type="date" id="eventEndDate" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="saveEvent" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update & Delete Event Modal -->
    <div class="modal fade" id="updateDeleteEventModal" tabindex="-1" role="dialog" aria-labelledby="updateDeleteEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDeleteEventModalLabel">Update / Delete Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="updateEventTitle" class="form-label">Title:</label>
                        <input type="text" class="form-control" id="updateEventTitle">
                    </div>
                    <div class="mb-3">
                        <label for="updateEventDescription" class="form-label">Description:</label>
                        <textarea class="form-control" id="updateEventDescription"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="updateEventStart" class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="updateEventStart" >
                    </div>
                    <div class="mb-3">
                        <label for="updateEventEnd" class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="updateEventEnd">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="updateEventButton" class="btn btn-primary">Update</button>
                    <button type="button" id="deleteEventButton" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>




    <script>
    function showAlert(message, alertType = 'success', duration = 4000) {
        var alertDiv = $('#alertMessage');
        var alertText = $('#alertText');

        alertDiv.removeClass('alert-success alert-danger alert-warning alert-info');
        alertDiv.addClass('alert-' + alertType);
        alertText.text(message);

        alertDiv.fadeIn();

        setTimeout(function () {
            alertDiv.fadeOut();
        }, duration);
    }

    var base_url = "<?php echo base_url(); ?>";

    $(document).ready(function () {

        let deleteEventId = null; // Store the event ID to be deleted
        let selectedEventId = null;

        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            events: base_url + 'calendar/get_events', // Fetch events from Calendar controller

             // Customizing the display of event titles in the calendar
            eventContent: function(info) {
                // Format the start date to show only the date without the time (e.g., '2024-11-27')
                let startDate = info.event.start.toLocaleDateString();
                let title = info.event.title; // Get the event's title

                // Return the custom HTML content for the event, showing both title and start date
                return {
                   // html: `<div class="fc-event-title">${title}</div><div class="fc-event-date">${startDate}</div>`
                   html: `<div class="fc-event-title">${title}  ${startDate}</div>`

                };
            },

            // Add Event
            select: function (info) {
                $('#eventModal').modal('show');
                $('#eventStartDate').val(info.startStr); // Set start date
                $('#eventEndDate').val(''); // Clear end date

                $('#saveEvent').off('click').on('click', function () {
                    const title = $('#eventTitle').val();
                    const description = $('#eventDescription').val();
                    const startDate = $('#eventStartDate').val();
                    const endDate = $('#eventEndDate').val();

                    if (!title) {
                        showAlert('Title is required.', 'warning');
                        return;
                    }

                    if (!endDate) {
                        showAlert('End date is required.', 'warning');
                        return;
                    }

                    // Check if end date is less than start date
                    if (new Date(endDate) < new Date(startDate)) {
                        showAlert('End date must not be earlier than start date.', 'warning');
                        return;
                    }

                    $.ajax({
                        url: base_url + 'calendar/add_event',
                        method: 'POST',
                        data: {
                            title: title,
                            description: description,
                            start: startDate,
                            end: endDate
                        },
                        success: function () {
                            calendar.refetchEvents();
                            showAlert('Event added successfully!', 'success');
                            $('#eventModal').modal('hide');
                             // Clear modal data
                            $('#eventTitle').val('');
                            $('#eventDescription').val('');
                            $('#eventStartDate').val('');
                            $('#eventEndDate').val('');
                        },
                        error: function () {
                            showAlert('Error adding event.', 'danger');
                        }
                    });
                });
            },

            // Update Event by dragging 
           
            eventDrop: function (info) {
                const event = info.event;

                // Get the current start date and time
                let startDate = event.start;

                // Add one day to the start date
                startDate.setDate(startDate.getDate());  

                // If event has an end date, do the same for the end date
                let endDate = event.end ? new Date(event.end) : null;
                

                $.ajax({
                    url: base_url + 'calendar/update_event/' + event.id,
                    method: 'PUT',
                    data: {
                        title: event.title,
                        description: event.extendedProps.description,
                        start: startDate.toISOString(),  // Send the updated start date as ISO string
                        end: endDate ? endDate.toISOString() : null  // Send the updated end date (if it exists)
                    },
                    success: function () {
                        showAlert('Event updated successfully!', 'success');
                    },
                    error: function () {
                        showAlert('Error updating event.', 'danger');
                    }
                });
            },

            
         
            eventClick: function (info) {
                const event = info.event;

                // Populate the modal with event details
                $('#updateEventTitle').val(event.title || '');
                $('#updateEventDescription').val(event.extendedProps.description || '');

                // Adjust start and end dates for time zone or ensure no time part affects the display
                const startDate = event.start;
                const endDate = event.end || event.start;  // Default to start date if end is not defined

                // Adjust to local date string (this removes time components)
                $('#updateEventStart').val(startDate ? startDate.toLocaleDateString('en-CA') : '');  // 'en-CA' gives yyyy-mm-dd format
                $('#updateEventEnd').val(endDate ? endDate.toLocaleDateString('en-CA') : '');  // 'en-CA' gives yyyy-mm-dd format

                // Store the selected event ID for updates and deletion
                selectedEventId = event.id;

                // Show the modal
                $('#updateDeleteEventModal').modal('show');
            }

        });

        calendar.render();

        // Handle Update Event
        $('#updateEventButton').on('click', function () {
            if (selectedEventId) {
                const title = $('#updateEventTitle').val();
                const description = $('#updateEventDescription').val();
                const startDate = $('#updateEventStart').val();
                const endDate = $('#updateEventEnd').val();

                if (!title) {
                    showAlert('Title is required.', 'warning');
                    return;
                }

                if (!endDate || new Date(endDate) < new Date(startDate)) {
                    showAlert('End date must not be before start date.', 'warning');
                    return;
                }

                $.ajax({
                    url: base_url + 'calendar/update_event/' + selectedEventId,
                    method: 'PUT',
                    data: {
                        title: title,
                        description: description,
                        start: startDate,
                        end: endDate
                    },
                    success: function () {
                        calendar.refetchEvents();
                        showAlert('Event updated successfully!', 'success');
                        $('#updateDeleteEventModal').modal('hide');
                    },
                    error: function () {
                        showAlert('Error updating event.', 'danger');
                    }
                });
            }
        });

        // Handle Delete Event
        $('#deleteEventButton').on('click', function () {
            if (selectedEventId) {
                if (confirm('Are you sure you want to delete this event?')) {
                    $.ajax({
                        url: base_url + 'calendar/delete_event/' + selectedEventId,
                        method: 'DELETE',
                        success: function () {
                            calendar.refetchEvents();
                            showAlert('Event deleted successfully!', 'success');
                            $('#updateDeleteEventModal').modal('hide');
                        },
                        error: function () {
                            showAlert('Error deleting event.', 'danger');
                        }
                    });
                }
            }
        });
        
    });
    </script>


</body>
</html>
