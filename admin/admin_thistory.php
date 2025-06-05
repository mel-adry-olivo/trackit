<?php

include "../database/queries.php";

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userCode = $_SESSION["user_code"] ?? null;
$taskHistory = getPastTasks();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Task History</title>
  <link rel="stylesheet" href="../assets/css/styles.css" />
  <script src="../assets/js/main/clock.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body class="manager">

  <div class="top-banner" style="
  background: url('./assets/img/banners/22.jpg') no-repeat center center / cover;
    width: 100%;
    height: 40%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
">

  </div>
  <div class="mgr-container">

    <?php include "../php/includes/adm_sidebar.php"; ?>

    <main class="mgr-main-content">
      <header class="mgr-header">
        <div>
          <h1>Welcome, <?= $userFullname ?></h1>
          <p class="mgr-id"><b>Employee</b> || ID No.: <?= $userCode ?>
          </p>
        </div>
        <div class="mgr-branch-info">
          <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
          <span id="clock">--:--:--</span>
        </div>
      </header>

      <div class="mgr-search-filter">
        <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" />
        <button id="exportPdfBtn" class="mgr-pdf-btn" onclick="exportToPDF()">

          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M12 15V3M12 15L8 11M12 15L16 11M5 18C3.89543 18 3 18.8954 3 20C3 21.1046 3.89543 22 5 22C6.10457 22 7 21.1046 7 20C7 18.8954 6.10457 18 5 18ZM19 18C17.8954 18 17 18.8954 17 20C17 21.1046 17.8954 22 19 22C20.1046 22 21 21.1046 21 20C21 18.8954 20.1046 18 19 18Z"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Save as PDF
        </button>
      </div>
      <div class="mgr-title">
        <h1><br>Task History</h1><br>
        <p>Review all completed and past tasks in one place. This page allows admins and managers to track team
          performance, monitor progress, and ensure accountability over time.</p><br><br><br>
      </div>
      <section class="mgr-th-task-table" id="pdfContent">
        <table>
          <thead>
            <tr>
              <th>ASSIGNEE</th>
              <th>ID NO.</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody id="mgr-th-taskBody">
            <?php foreach ($taskHistory as $userCode => $data): ?>
              <tr>
                <td><?= htmlspecialchars($data['name']) ?></td>
                <td><?= $userCode ?></td>
                <td><button class="mgr-th-toggle-btn" onclick="mgrThToggleDetails(this)">View Details ▼</button></td>
              </tr>
              <tr class="mgr-th-details-row">
                <td colspan="3">
                  <table class="mgr-th-inner-table">
                    <thead>
                      <tr>
                        <th>TASK</th>
                        <th>DATE</th>
                        <th>TIMELINE</th>
                        <th>STATUS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($data['tasks'] as $task): ?>
                        <tr>
                          <td><?= htmlspecialchars($task['description']) ?></td>
                          <td><?= htmlspecialchars(date("M d, Y", strtotime($task['start_date']))) ?> -
                            <?= htmlspecialchars(date("M d, Y", strtotime($task['end_date']))) ?>
                          </td>
                          <td><?= htmlspecialchars($task['timeline']) ?></td>
                          <td><?= htmlspecialchars($task['status']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </section>

      <script>

        const { jsPDF } = window.jspdf;

        function exportToPDF() {
          const element = document.getElementById('pdfContent');
          const options = {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            logging: false
          };

          html2canvas(element, options).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgWidth = 210;
            const pageHeight = 295;
            const imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;
            let position = 0;

            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
              position = heightLeft - imgHeight;
              pdf.addPage();
              pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
              heightLeft -= pageHeight;
            }

            pdf.save('Task_History_Report.pdf');
          });
        }


        function mgrThToggleDetails(button) {
          const summaryRow = button.closest('tr');
          const detailsRow = summaryRow.nextElementSibling;

          if (detailsRow && detailsRow.classList.contains('mgr-th-details-row')) {
            const isVisible = detailsRow.classList.toggle('show');
            button.textContent = isVisible ? 'Close History ▲' : 'View Details ▼';
            summaryRow.classList.toggle('mgr-th-highlight', isVisible);
            detailsRow.classList.toggle('mgr-th-highlight', isVisible);
          }
        }

      </script>
      <script>
        function searchTasks() {
          const input = document.getElementById("searchBar").value.toLowerCase();
          const rows = document.querySelectorAll("#mgr-th-taskBody > tr");

          for (let i = 0; i < rows.length; i += 2) {
            const summaryRow = rows[i];
            const detailsRow = rows[i + 1];

            const name = summaryRow.cells[0].textContent.toLowerCase();
            const id = summaryRow.cells[1].textContent.toLowerCase();

            // Include nested task details
            const taskCells = detailsRow.querySelectorAll("tbody td");
            let taskText = '';
            taskCells.forEach(td => taskText += td.textContent.toLowerCase() + " ");

            const combinedText = name + " " + id + " " + taskText;

            if (combinedText.includes(input)) {
              summaryRow.style.display = "";
              detailsRow.style.display = "";

              // Expand the row
              if (!detailsRow.classList.contains("show")) {
                detailsRow.classList.add("show");
                summaryRow.querySelector("button").textContent = "Close History ▲";
              }
            } else {
              summaryRow.style.display = "none";
              detailsRow.style.display = "none";
            }

          }
        }
      </script>

</body>

</html>