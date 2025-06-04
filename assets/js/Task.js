// task_assign_script.js

let fetchedEmployees = [];
const tasks = [ // This list is no longer used for a dropdown, but kept for potential future use or reference.
    "Staff: Daily Cleaning - Counters", "Staff: Daily Cleaning - Sinks", "Staff: Daily Cleaning - Trash Cans", "Staff: Daily Cleaning - Floor",
    "Staff: Daily Cleaning - Windows", "Staff: Daily Cleaning - Espresso Machine", "Staff: Daily Cleaning - Grinder", "Staff: Daily Cleaning - Milk Jugs",
    "Prepare Ingredients & Supplies", "Shop Opening",
    "POS Manager: Receive Order Details", "POS Manager: Receive Payment", "POS Manager: Input order details in Loyverse POS App",
    "POS Manager: Print order receipt", "POS Manager: Send the receipt for reference to the Barista",
    "Write the Petty Cash", "Take Payments", "List down the Delivery Fees",
    "Barista: Prepare ingredients", "Barista: Grind & Roast beans", "Cleaning", "Check Inventory", "Product Delivery"
];
const DEFAULT_AVATAR = 'assets/img/default_avatar.png'; // Define default avatar path

async function loadEmployees() {
    try {
        const response = await fetch('actions/get_employees.php');
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Failed to fetch employees:', response.status, errorText);
            alert(`Error loading employee data: ${response.statusText}. Server said: ${errorText}. Please check console.`);
            return false;
        }
        const data = await response.json();
        if (data.success && Array.isArray(data.employees)) {
            fetchedEmployees = data.employees.map(emp => ({
                ...emp,
                img: emp.img || DEFAULT_AVATAR 
            }));
            console.log("Employees loaded:", fetchedEmployees);
            return true;
        } else {
            console.error('Error fetching employees from server response:', data.message);
            alert('Error: Could not parse employee data. ' + (data.message || 'Unknown server error.'));
            return false;
        }
    } catch (error) {
        console.error('Network error while fetching employees:', error);
        alert(`Network error: Could not connect to load employee data. ${error.message}`);
        return false;
    }
}

function createEmployeeDisplay(employee) {
    const container = document.createElement("div");
    container.style.display = "flex";
    container.style.alignItems = "center";
    container.innerHTML = `
      <img src="${employee.img}" alt="${employee.name}" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 8px;">
      <span>${employee.name}</span>
    `;
    return container;
}

function calculateTimeline(elem) {
    const row = elem.closest("tr");
    if (!row) return;

    const startInputElem = row.querySelector(".start-date");
    const endInputElem = row.querySelector(".end-date");
    
    if (!startInputElem || !endInputElem) {
        // This might happen if called when inputs are not present.
        // updateStatusCounts is more robust for reading final dates from divs.
    }

    const startInputVal = startInputElem?.value;
    const endInputVal = endInputElem?.value;
    
    const timelineCell = row.querySelector(".timeline");
    const priorityCell = row.cells[6]; // 7th cell
    const statusCell = row.cells[5];   // 6th cell

    if (!timelineCell || !priorityCell || !statusCell) {
        console.error("Timeline, priority, or status cell not found in row for timeline calculation.");
        return;
    }

    priorityCell.className = "stat"; // Reset priority classes, keep base 'stat'

    if (startInputVal && endInputVal) {
        const startDate = new Date(startInputVal);
        const endDate = new Date(endInputVal);

        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
            timelineCell.textContent = "Invalid Dates";
            priorityCell.classList.add("priority-invalid");
            priorityCell.textContent = "Invalid";
            statusCell.textContent = "Invalid"; 
            updateStatusCounts();
            return;
        }

        const diffTime = endDate.getTime() - startDate.getTime(); 
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 

        if (diffDays > 0) {
            timelineCell.textContent = `${diffDays} day${diffDays !== 1 ? 's' : ''}`;
            if (diffDays <= 3) {
                priorityCell.textContent = "High";
                priorityCell.classList.add("priority-high");
            } else if (diffDays <= 7) {
                priorityCell.textContent = "Medium";
                priorityCell.classList.add("priority-medium");
            } else if (diffDays <= 100) { 
                priorityCell.textContent = "Low";
                priorityCell.classList.add("priority-low");
            } else {
                priorityCell.textContent = "Undefined"; 
                priorityCell.classList.add("priority-un"); 
            }
        } else {
            timelineCell.textContent = "Invalid Range"; 
            priorityCell.classList.add("priority-invalid");
            priorityCell.textContent = "Invalid";
            statusCell.textContent = "Invalid"; 
        }
    } else {
        timelineCell.textContent = "-";
        priorityCell.textContent = ""; 
    }
    updateStatusCounts(); 
}

function searchTasks() {
    const input = document.getElementById("searchBar").value.toLowerCase();
    const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");
    rows.forEach(row => {
        const textContent = Array.from(row.cells).map(cell => cell.textContent).join(' ').toLowerCase();
        row.style.display = textContent.includes(input) ? "" : "none";
    });
}

function updateClock() {
    const clockElement = document.getElementById("clock");
    if (clockElement) {
        clockElement.textContent = new Date().toLocaleTimeString();
    }
}

function filterStatus(statusToFilter) {
    const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");
    rows.forEach(row => {
        const rowStatusCell = row.cells[5]; 
        if (rowStatusCell) {
            const currentStatus = rowStatusCell.innerText.trim();
            row.style.display = currentStatus === statusToFilter || statusToFilter === 'All' ? '' : 'none';
        }
    });
    document.getElementById("searchBar").value = ""; 
}

function updateStatusCounts() {
    const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const counts = { "Not Started": 0, "In Progress": 0, "Done": 0, "Overdue": 0, "Invalid": 0 };

    rows.forEach(row => {
        const statusCell = row.cells[5];    
        const timelineCell = row.querySelector(".timeline"); 
        const startInput = row.querySelector(".start-date"); 
        const endInput = row.querySelector(".end-date");    

        let startDateValue, endDateValue;

        if (startInput?.value && endInput?.value) { 
            startDateValue = startInput.value;
            endDateValue = endInput.value;
        } else { 
            const dateCell = row.cells[3]; 
            const startDivText = dateCell.querySelector("div:nth-child(1)")?.textContent;
            const endDivText = dateCell.querySelector("div:nth-child(2)")?.textContent;
            if (startDivText?.includes("Start:")) startDateValue = startDivText.split(":")[1]?.trim();
            if (endDivText?.includes("End:")) endDateValue = endDivText.split(":")[1]?.trim();
        }
        
        if (timelineCell?.textContent.toLowerCase().includes("invalid") || 
            timelineCell?.textContent.toLowerCase().includes("invalid dates") || 
            timelineCell?.textContent.toLowerCase().includes("invalid range")) { 
            statusCell.textContent = "Invalid";
            counts["Invalid"]++;
            return;
        }

        if (!startDateValue || !endDateValue) { 
            if (statusCell.textContent !== "Done") { 
                 statusCell.textContent = "Not Started";
            }
            counts[statusCell.textContent]++; 
            return;
        }
        
        const startDate = new Date(startDateValue);
        const endDate = new Date(endDateValue);
        startDate.setHours(0,0,0,0);
        endDate.setHours(0,0,0,0);

        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime()) || endDate < startDate) {
            statusCell.textContent = "Invalid";
            counts["Invalid"]++;
            return;
        }

        if (statusCell.textContent === "Done") { 
            counts["Done"]++;
        } else if (endDate < today) {
            statusCell.textContent = "Overdue";
            counts["Overdue"]++;
        } else if (startDate <= today && endDate >= today) {
            statusCell.textContent = "In Progress";
            counts["In Progress"]++;
        } else if (startDate > today) {
            statusCell.textContent = "Not Started";
            counts["Not Started"]++;
        } else { 
            statusCell.textContent = "Not Started"; 
            counts["Not Started"]++;
        }
    });

    document.getElementById("count-not-started").textContent = counts["Not Started"];
    document.getElementById("count-in-progress").textContent = counts["In Progress"];
    document.getElementById("count-done").textContent = counts["Done"];
    document.getElementById("count-overdue").textContent = counts["Overdue"];
}

function addNewTaskRow() {
    if (fetchedEmployees.length === 0) {
        alert("Employee data is still loading or failed to load. Please wait or try refreshing. Cannot add new task row.");
        return;
    }

    const tableBody = document.getElementById("taskBody");
    const addTaskRow = document.getElementById("add-task-row"); 
    const newRow = tableBody.insertRow(tableBody.rows.length - (addTaskRow ? 1:0)); 

    const assigneeCell = newRow.insertCell(); 
    const idCell = newRow.insertCell();       
    idCell.classList.add("employee-id");
    idCell.textContent = "-";
    const taskCell = newRow.insertCell();     
    const dateCell = newRow.insertCell();     
    const timelineCell = newRow.insertCell(); 
    timelineCell.classList.add("timeline");
    timelineCell.textContent = "-";
    const statusCell = newRow.insertCell();   
    statusCell.textContent = "Not Started"; 
    const priorityCell = newRow.insertCell(); 
    priorityCell.classList.add("stat");
    priorityCell.textContent = "-";
    const actionCell = newRow.insertCell();   
    actionCell.classList.add("action-cell");

    const assigneeSelect = document.createElement("select");
    assigneeSelect.classList.add("assignee-select");
    assigneeSelect.innerHTML = `<option value="" disabled selected>Select Employee</option>`;
    fetchedEmployees.forEach(emp => {
        const option = document.createElement("option");
        option.value = emp.id; 
        option.textContent = emp.name;
        option.dataset.img = emp.img; 
        assigneeSelect.appendChild(option);
    });
    assigneeCell.appendChild(assigneeSelect);

    assigneeSelect.addEventListener("change", function () {
        const selectedId = this.value; 
        const selectedEmployee = fetchedEmployees.find(emp => String(emp.id) === selectedId);
        if (selectedEmployee) {
            idCell.textContent = selectedEmployee.id;
            assigneeCell.innerHTML = ""; 
            assigneeCell.appendChild(createEmployeeDisplay(selectedEmployee));
        }
    });

    // --- MODIFIED PART: Task input changed from select to text input ---
    const taskInput = document.createElement("input");
    taskInput.type = "text";
    taskInput.classList.add("task-input"); // Added class for easier selection/styling
    taskInput.placeholder = "Enter Task Description";
    taskCell.appendChild(taskInput);
    // No event listener needed to update cell text immediately, value will be read on save.
    // --- END OF MODIFIED PART ---

    const startDateInput = document.createElement("input");
    startDateInput.type = "date";
    startDateInput.classList.add("start-date");
    startDateInput.addEventListener("change", () => calculateTimeline(startDateInput));

    const endDateInput = document.createElement("input");
    endDateInput.type = "date";
    endDateInput.classList.add("end-date");
    endDateInput.addEventListener("change", () => calculateTimeline(endDateInput));

    dateCell.appendChild(startDateInput);
    dateCell.appendChild(document.createTextNode(" to "));
    dateCell.appendChild(endDateInput);

    actionCell.innerHTML = `
      <button class="editBtn" onclick="enableEdit(this)" title="Edit this task">Edit</button>
      <button class="deleteBtn" onclick="deleteRow(this)" title="Delete this task">Delete</button>
    `;

    updateStatusCounts();
}

function enableEdit(button) {
    const row = button.closest("tr");
    const assigneeCell = row.cells[0];
    const idCell = row.cells[1];
    const taskCell = row.cells[2];
    const dateCell = row.cells[3];
    const actionCell = row.cells[7];

    row.dataset.originalAssigneeHTML = assigneeCell.innerHTML;
    row.dataset.originalIdText = idCell.textContent.trim();
    row.dataset.originalTaskText = taskCell.textContent.trim();
    row.dataset.originalDateHTML = dateCell.innerHTML;

    const currentEmployeeId = idCell.textContent.trim(); 
    const assigneeSelect = document.createElement("select");
    assigneeSelect.classList.add("assignee-select");
    assigneeSelect.innerHTML = `<option value="" disabled>Select Employee</option>`;
    fetchedEmployees.forEach(emp => {
        const option = document.createElement("option");
        option.value = emp.id; 
        option.textContent = emp.name;
        option.dataset.img = emp.img;
        if (String(emp.id) === currentEmployeeId) { 
            option.selected = true;
        }
        assigneeSelect.appendChild(option);
    });
    assigneeCell.innerHTML = "";
    assigneeCell.appendChild(assigneeSelect);

    // --- MODIFIED PART: Task input changed from select to text input during edit ---
    const currentTask = taskCell.textContent.trim();
    const taskInput = document.createElement("input");
    taskInput.type = "text";
    taskInput.classList.add("task-input");
    taskInput.value = currentTask;
    taskInput.placeholder = "Enter Task Description";
    taskCell.innerHTML = "";
    taskCell.appendChild(taskInput);
    // --- END OF MODIFIED PART ---

    let originalStart = "", originalEnd = "";
    if (row.dataset.originalDateHTML.includes("<strong>Start:</strong>")) {
        originalStart = row.dataset.originalDateHTML.match(/<strong>Start:<\/strong>\s*([\d-]+)/)?.[1] || "";
        originalEnd = row.dataset.originalDateHTML.match(/<strong>End:<\/strong>\s*([\d-]+)/)?.[1] || "";
    } else if (row.querySelector(".start-date")) { 
        originalStart = row.querySelector(".start-date").value;
        originalEnd = row.querySelector(".end-date").value;
    }

    dateCell.innerHTML = "";
    const startInput = document.createElement("input");
    startInput.type = "date"; startInput.classList.add("start-date"); startInput.value = originalStart;
    const endInput = document.createElement("input");
    endInput.type = "date"; endInput.classList.add("end-date"); endInput.value = originalEnd;
    dateCell.appendChild(startInput);
    dateCell.appendChild(document.createTextNode(" to "));
    dateCell.appendChild(endInput);
    startInput.addEventListener("change", () => calculateTimeline(startInput));
    endInput.addEventListener("change", () => calculateTimeline(endInput));

    actionCell.innerHTML = `
      <button onclick="saveRow(this)" title="Save changes">Save</button>
      <button onclick="cancelEdit(this)" title="Cancel editing">Cancel</button>
    `;
    if (originalStart && originalEnd) calculateTimeline(startInput); 
}

function saveRow(button) {
    const row = button.closest("tr");
    const assigneeCell = row.cells[0];
    const idCell = row.cells[1];
    const taskCell = row.cells[2];
    const dateCell = row.cells[3];
    const actionCell = row.cells[7];

    const assigneeSelect = assigneeCell.querySelector("select.assignee-select");
    // --- MODIFIED PART: Get task from text input ---
    const taskInput = taskCell.querySelector("input.task-input"); 
    // --- END OF MODIFIED PART ---
    const startDateInput = dateCell.querySelector("input.start-date");
    const endDateInput = dateCell.querySelector("input.end-date");

    if (!assigneeSelect?.value) { alert("Please select an assignee."); assigneeSelect?.focus(); return; }
    // --- MODIFIED PART: Validate task input ---
    if (!taskInput?.value.trim()) { alert("Please enter a task description."); taskInput?.focus(); return; }
    // --- END OF MODIFIED PART ---
    if (!startDateInput?.value) { alert("Please select a start date."); startDateInput?.focus(); return; }
    if (!endDateInput?.value) { alert("Please select an end date."); endDateInput?.focus(); return; }
    
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);
    if (isNaN(startDate.getTime()) || isNaN(endDate.getTime()) || endDate < startDate) {
        alert("Invalid date range. End date cannot be before start date, and dates must be valid.");
        return;
    }

    const selectedEmployee = fetchedEmployees.find(emp => String(emp.id) === assigneeSelect.value);
    if (selectedEmployee) {
        idCell.textContent = selectedEmployee.id;
        assigneeCell.innerHTML = "";
        assigneeCell.appendChild(createEmployeeDisplay(selectedEmployee));
    }

    // --- MODIFIED PART: Set task cell content from text input ---
    taskCell.textContent = taskInput.value.trim();
    // --- END OF MODIFIED PART ---


    dateCell.innerHTML = `
      <div><strong>Start:</strong> ${startDateInput.value}</div>
      <div><strong>End:</strong> ${endDateInput.value}</div>
    `;

    const priorityCell = row.cells[6];
    priorityCell.className = "stat"; 
    const diffTime = endDate.getTime() - startDate.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
    if (diffDays > 0) {
        if (diffDays <= 3) { priorityCell.textContent = "High"; priorityCell.classList.add("priority-high"); }
        else if (diffDays <= 7) { priorityCell.textContent = "Medium"; priorityCell.classList.add("priority-medium"); }
        else if (diffDays <= 100) { priorityCell.textContent = "Low"; priorityCell.classList.add("priority-low"); }
        else { priorityCell.textContent = "Undefined"; priorityCell.classList.add("priority-un"); }
        row.querySelector(".timeline").textContent = `${diffDays} day${diffDays !== 1 ? 's' : ''}`;
    } else {
        priorityCell.textContent = "Invalid"; priorityCell.classList.add("priority-invalid");
        row.querySelector(".timeline").textContent = "Invalid Range";
    }


    actionCell.innerHTML = `
      <button class="editBtn" onclick="enableEdit(this)" title="Edit this task">Edit</button>
      <button class="deleteBtn" onclick="deleteRow(this)" title="Delete this task">Delete</button>
    `;
    updateStatusCounts(); 
}

function cancelEdit(button) {
    const row = button.closest("tr");
    row.cells[0].innerHTML = row.dataset.originalAssigneeHTML || "";
    row.cells[1].textContent = row.dataset.originalIdText || "-";
    row.cells[2].textContent = row.dataset.originalTaskText || ""; // This will restore the text, removing any input field
    row.cells[3].innerHTML = row.dataset.originalDateHTML || "";
    
    row.cells[7].innerHTML = `
      <button class="editBtn" onclick="enableEdit(this)" title="Edit this task">Edit</button>
      <button class="deleteBtn" onclick="deleteRow(this)" title="Delete this task">Delete</button>
    `;

    delete row.dataset.originalAssigneeHTML;
    delete row.dataset.originalIdText;
    delete row.dataset.originalTaskText;
    delete row.dataset.originalDateHTML;

    const startInputInRestored = row.querySelector(".start-date");
    if (startInputInRestored) { 
        calculateTimeline(startInputInRestored);
    } else { 
        const dateCell = row.cells[3];
        const priorityCell = row.cells[6];
        const timelineCell = row.cells[4];
        priorityCell.className = "stat"; 

        let startDateValue, endDateValue;
        const startDivText = dateCell.querySelector("div:nth-child(1)")?.textContent;
        const endDivText = dateCell.querySelector("div:nth-child(2)")?.textContent;
        if (startDivText?.includes("Start:")) startDateValue = startDivText.split(":")[1]?.trim();
        if (endDivText?.includes("End:")) endDateValue = endDivText.split(":")[1]?.trim();

        if (startDateValue && endDateValue) {
            const startDate = new Date(startDateValue);
            const endDate = new Date(endDateValue);
            if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime()) && endDate >= startDate) {
                const diffTime = endDate.getTime() - startDate.getTime();
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                timelineCell.textContent = `${diffDays} day${diffDays !== 1 ? 's' : ''}`;
                if (diffDays <= 3) { priorityCell.textContent = "High"; priorityCell.classList.add("priority-high");}
                else if (diffDays <= 7) { priorityCell.textContent = "Medium"; priorityCell.classList.add("priority-medium");}
                else if (diffDays <= 100) { priorityCell.textContent = "Low"; priorityCell.classList.add("priority-low");}
                else { priorityCell.textContent = "Undefined"; priorityCell.classList.add("priority-un");}
            } else {
                timelineCell.textContent = "Invalid Range";
                priorityCell.textContent = "Invalid"; priorityCell.classList.add("priority-invalid");
            }
        } else {
            timelineCell.textContent = "-";
            priorityCell.textContent = "";
        }
    }
    updateStatusCounts();
}

function deleteRow(button) {
    const modal = document.getElementById("deleteModal"); 
    const rowToDelete = button.closest("tr");

    if (!rowToDelete) return; 

    if (modal) {
        modal.style.display = "flex"; 
        const deleteConfirmBtn = document.getElementById("deleteConfirmBtn"); 
        
        const newDeleteConfirmBtn = deleteConfirmBtn.cloneNode(true);
        deleteConfirmBtn.parentNode.replaceChild(newDeleteConfirmBtn, deleteConfirmBtn);

        newDeleteConfirmBtn.onclick = () => {
            rowToDelete.remove();
            modal.style.display = "none";
            updateStatusCounts();
        };
        const deleteCancelBtn = document.getElementById("deleteCancelBtn"); 
        if (deleteCancelBtn) {
            deleteCancelBtn.onclick = () => modal.style.display = "none";
        }
    } else { 
        if (confirm("Are you sure you want to delete this task?")) {
            rowToDelete.remove();
            updateStatusCounts();
        }
    }
}

function closeModal(modalId) { 
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = "none";
}

const submitBtn = document.getElementById("submitBtn");
if (submitBtn) {
    submitBtn.addEventListener("click", async () => {
        // ... (initial validation and tasksToSubmit array setup remains the same) ...

        submitBtn.disabled = true;
        submitBtn.textContent = "Submitting...";
        let allSubmissionsSuccessful = true;
        const submissionResults = [];

        for (const taskDetail of tasksToSubmit) {
            const formData = new FormData();
            formData.append('employeeId', taskDetail.employeeId);
            formData.append('task', taskDetail.task);
            formData.append('startDate', taskDetail.startDate);
            formData.append('endDate', taskDetail.endDate);

            let responseText = ''; // To store the raw response text

            try {
                const response = await fetch('actions/add_task.php', { method: 'POST', body: formData });
                responseText = await response.text(); // Read the response as text first

                const empName = fetchedEmployees.find(e => String(e.id) === taskDetail.employeeId)?.name || `ID ${taskDetail.employeeId}`;

                if (!response.ok) { // Handles HTTP errors (e.g., 404, 500)
                    allSubmissionsSuccessful = false;
                    taskDetail.rowElement.style.backgroundColor = "#f8d7da";
                    // responseText here will contain the server's error page (often HTML)
                    submissionResults.push(`ERROR (HTTP ${response.status}): Task "${taskDetail.task}" for ${empName} - ${response.statusText}. Server response: ${responseText.substring(0, 150)}...`);
                    console.error(`HTTP error for ${empName}, task "${taskDetail.task}":`, response.status, response.statusText, "Full server response:", responseText);
                    continue; // Skip to the next task
                }

                // If response.ok, try to parse the responseText as JSON
                try {
                    const result = JSON.parse(responseText); // Parse the text we already fetched

                    if (result.success) {
                        taskDetail.rowElement.style.backgroundColor = "#d4edda";
                        submissionResults.push(`SUCCESS: Task "${taskDetail.task}" for ${empName}.`);
                        taskDetail.rowElement.querySelectorAll('button.editBtn, button.deleteBtn').forEach(b => b.disabled = true);
                    } else {
                        allSubmissionsSuccessful = false;
                        taskDetail.rowElement.style.backgroundColor = "#f8d7da";
                        const message = result.message || "Unknown server error after submission.";
                        submissionResults.push(`ERROR (App Level): Task "${taskDetail.task}" for ${empName} - ${message}`);
                        console.error(`Failed submission (app-level) for ${empName}, task "${taskDetail.task}":`, result);
                        if (result.debug_session) {
                            console.log("PHP Session Debug Info:", result.debug_session);
                        }
                    }
                } catch (jsonParseError) { // Catches errors from JSON.parse (e.g., "Unexpected token '<'")
                    allSubmissionsSuccessful = false;
                    taskDetail.rowElement.style.backgroundColor = "#f8d7da";
                    // This is the scenario from your screenshot.
                    // responseText contains the actual HTML/PHP error output from the server.
                    submissionResults.push(`ERROR (Invalid JSON): Task "${taskDetail.task}" for ${empName}. Server returned non-JSON content: ${responseText.substring(0, 150)}...`);
                    console.error(`JSON Parse Error for ${empName}, task "${taskDetail.task}":`, jsonParseError, "Full server response:", responseText);
                }

            } catch (networkError) { // Catches actual network errors (e.g., fetch failed to connect)
                allSubmissionsSuccessful = false;
                taskDetail.rowElement.style.backgroundColor = "#f8d7da";
                const empName = fetchedEmployees.find(e => String(e.id) === taskDetail.employeeId)?.name || `ID ${taskDetail.employeeId}`;
                submissionResults.push(`ERROR (Network): Task "${taskDetail.task}" for ${empName} - ${networkError.message}`);
                console.error(`Network error for ${empName}, task "${taskDetail.task}":`, networkError, "Response text (if any):", responseText);
            }
        }

        submitBtn.disabled = false;
        submitBtn.textContent = "Submit All Tasks";

        let finalMessage = "Submission process finished.\n\nResults:\n" + submissionResults.join("\n");
        if (allSubmissionsSuccessful) {
            finalMessage += "\n\nAll tasks were submitted successfully!";
        } else {
            finalMessage += "\n\nSome tasks had errors. Please review the highlighted rows, console logs, and alert messages for details.";
        }
        alert(finalMessage);
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    const loaded = await loadEmployees();
    const addTaskBtn = document.getElementById("addTaskBtn");

    if (!loaded) {
        if (addTaskBtn) {
            addTaskBtn.disabled = true;
            addTaskBtn.title = "Employee data failed to load. Cannot add tasks.";
        }
    }

    if (document.getElementById("clock")) {
        setInterval(updateClock, 1000);
        updateClock();
    }

    updateStatusCounts(); 

    if (addTaskBtn) {
        addTaskBtn.addEventListener("click", addNewTaskRow);
    } else {
        console.warn("#addTaskBtn not found. Adding new rows via button click will not work.");
    }

    const filterContainer = document.getElementById("statusFilters"); 
    if (filterContainer) {
        filterContainer.addEventListener("click", function(event) {
            const button = event.target.closest("button");
            if (button && button.dataset.status) {
                filterStatus(button.dataset.status);
            }
        });
    } else {
        // Assuming filter buttons are directly clickable and have onclick attributes if #statusFilters is not used for delegation.
        // If you have individual buttons with onclick="filterStatus('StatusName')", no specific event listener here is strictly needed for them.
        // But the example HTML has buttons like <button onclick="filterStatus('All')">, so this delegated listener is more for a container.
        // For robustness, ensure filter buttons are either handled by individual onclicks or by a delegated listener on a common parent.
        // The provided HTML's filter buttons use direct onclick attributes, so this #statusFilters listener is a good-to-have but might not be strictly necessary
        // unless you refactor to remove inline onclicks.
    }
});