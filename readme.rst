### **README.md**

---
Muhammed Ameen 


# **Event Management Application**  

This is an event management application built with **CodeIgniter 3** and **jQuery**. It uses **FullCalendar** for an interactive calendar interface and allows users to manage events, including adding, editing, deleting, and dragging events.

---

## **Features**
- Add new events with title, description, and start/end dates.
- Edit existing events via a modal.
- Drag and drop events to update their dates.
- Delete events with confirmation.
- Automatically updates the database using AJAX requests.

---

## **Technologies Used**
- **Frontend:**
  - HTML, CSS, JavaScript
  - [FullCalendar](https://fullcalendar.io/)
  - jQuery
  - Bootstrap 

- **Backend:**
  - PHP (CodeIgniter 3 Framework)
  - MySQL Database

---


## **File Structure**

### **Frontend**  
- `application/views/calendar_view.php`: Contains the HTML, FullCalendar configuration, and modals.

### **Backend**  
- `application/controllers/Calendar.php`: Handles requests for fetching, adding, updating, and deleting events.
- `application/models/Event_model.php`: Handles database operations.

---

## **Database Schema**
### **Table: `events`**
| Column       | Type         | Description               |
|--------------|--------------|---------------------------|
| `id`         | INT (Primary Key, Auto Increment) | Unique identifier for each event. |
| `title`      | VARCHAR(255) | Event title.              |
| `description`| TEXT         | Event description.        |
| `start`      | DATETIME     | Event start date/time.    |
| `end`        | DATETIME     | Event end date/time.      |

---

## **How It Works**
1. **Add Event**  
   - Click on a date to open the add modal.  
   - Fill in the details and save. The event is added to the database.

2. **Edit Event**  
   - Click on an event to open the edit modal.  
   - Modify details and save. The database is updated.

3. **Delete Event**  
   - Click on an event and select delete.  
   - Confirm the deletion to remove the event.

4. **Drag Event**  
   - Drag an event to a new date. The change is saved to the database via AJAX.


## **Future Enhancements**
- Add user authentication for managing events.
- Implement recurring events functionality.
- Include a search or filter feature for events.

---

## **Contributors**
- **Muhammed Ameen** (PHP Developer)

---


Let me know if you'd like to tweak this further.