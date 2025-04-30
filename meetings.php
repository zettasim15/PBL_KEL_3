<html>
 <head>
  <title>Web Page</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
   body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #1a2b4c;
            color: #fff;
        }
        .header {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #1a2b4c;
        }
        .header input[type="text"] {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            margin-right: 20px;
        }
        .header .profile {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        .header .profile img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        .header .filter {
            display: flex;
            align-items: center;
            margin-right: auto;
        }
        .header .filter button {
            background-color: #2b3a5b;
            border: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .header .group {
            display: flex;
            align-items: center;
        }
        .header .group button {
            background-color: #2b3a5b;
            border: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .header .group i {
            margin-left: 10px;
        }
        .content {
            padding: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            color: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .table .create {
            padding: 10px;
            text-align: left;
            border-bottom: none;
        }
        .table .create button {
            background-color: #2b3a5b;
            border: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .image-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
  </style>
 </head>
 <body>
  <div class="header">
   <input placeholder="Search list" type="text"/>
   <div class="profile">
    <img alt="Profile picture" height="30" src="https://storage.googleapis.com/a1aa/image/Rg8zsnLSLCYmI1vMfDgwo2bgu44Xb8WtF7tCcHagWjMA269JA.jpg" width="30"/>
   </div>
   <div class="filter">
    <button>Filter <i class="fas fa-caret-down"></i></button>
   </div>
   <div class="group">
    <button>Group <i class="fas fa-caret-down"></i></button>
    <i class="fas fa-cog"></i>
   </div>
  </div>
  <div class="content">
   <table class="table" id="meetingsTable">
    <thead>
     <tr>
      <th>Name</th>
      <th>Due Date</th>
      <th>Status</th>
      <th>Upload By</th>
      <th>Actions</th>
     </tr>
    </thead>
    <tbody id="meetingsBody">
     <!-- Table rows will be dynamically added here -->
    </tbody>
   </table>
   <div class="image-container">
    <img alt="Decorative image" height="400" src="https://storage.googleapis.com/a1aa/image/HUj57Nrzg1bXG1keDBGHc3QlIDuXFYfRAWNas0TS5CaCs17TA.jpg" width="600"/>
   </div>
  </div>

  <!-- Modal for Creating/Editing -->
  <div id="meetingModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2>Create/Update Meeting</h2>
      <form id="meetingForm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="dueDate">Due Date:</label>
        <input type="date" id="dueDate" name="dueDate" required><br><br>
        <label for="status">Status:</label>
        <select id="status" name="status" required>
          <option value="todo">To Do</option>
          <option value="in-progress">In Progress</option>
          <option value="done">Done</option>
        </select><br><br>
        <label for="uploadedBy">Uploaded By:</label>
        <input type="text" id="uploadedBy" name="uploadedBy" required><br><br>
        <button type="submit">Save</button>
      </form>
    </div>
  </div>

  <script>
    let meetings = [
        {id: 1, name: "Meeting 1", dueDate: "2024-12-17", status: "todo", uploadedBy: "John Doe"},
        {id: 2, name: "Meeting 2", dueDate: "2024-12-18", status: "in-progress", uploadedBy: "Jane Smith"}
    ];

    // Function to display meetings in the table
    function renderMeetings() {
        const meetingsBody = document.getElementById("meetingsBody");
        meetingsBody.innerHTML = '';
        meetings.forEach((meeting) => {
            meetingsBody.innerHTML += `
                <tr>
                    <td>${meeting.name}</td>
                    <td>${meeting.dueDate}</td>
                    <td>${meeting.status}</td>
                    <td>${meeting.uploadedBy}</td>
                    <td>
                        <button onclick="editMeeting(${meeting.id})">Edit</button>
                        <button onclick="deleteMeeting(${meeting.id})">Delete</button>
                    </td>
                </tr>
            `;
        });
    }

    // Open modal for creating a new meeting
    function openModal() {
        document.getElementById("meetingModal").style.display = "block";
        document.getElementById("meetingForm").reset();
        document.getElementById("meetingForm").onsubmit = createMeeting;
    }

    // Close the modal
    function closeModal() {
        document.getElementById("meetingModal").style.display = "none";
    }

    // Create a new meeting
    function createMeeting(event) {
        event.preventDefault();
        const newMeeting = {
            id: meetings.length + 1,
            name: document.getElementById("name").value,
            dueDate: document.getElementById("dueDate").value,
            status: document.getElementById("status").value,
            uploadedBy: document.getElementById("uploadedBy").value
        };
        meetings.push(newMeeting);
        renderMeetings();
        closeModal();
    }

    // Edit an existing meeting
    function editMeeting(id) {
        const meeting = meetings.find(m => m.id === id);
        if (meeting) {
            document.getElementById("name").value = meeting.name;
            document.getElementById("dueDate").value = meeting.dueDate;
            document.getElementById("status").value = meeting.status;
            document.getElementById("uploadedBy").value = meeting.uploadedBy;
            document.getElementById("meetingForm").onsubmit = function(event) {
                updateMeeting(event, id);
            };
            openModal();
        }
    }

    // Update a meeting
    function updateMeeting(event, id) {
        event.preventDefault();
        const updatedMeeting = {
            id: id,
            name: document.getElementById("name").value,
            dueDate: document.getElementById("dueDate").value,
            status: document.getElementById("status").value,
            uploadedBy: document.getElementById("uploadedBy").value
        };
        const index = meetings.findIndex(m => m.id === id);
        if (index !== -1) {
            meetings[index] = updatedMeeting;
        }
        renderMeetings();
        closeModal();
    }

    // Delete a meeting
    function deleteMeeting(id) {
        meetings = meetings.filter(m => m.id !== id);
        renderMeetings();
    }

    // Initially render meetings
    renderMeetings();
  </script>
</body>
</html>
