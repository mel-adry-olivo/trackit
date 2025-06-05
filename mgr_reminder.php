<?php

include 'database/queries.php';

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userId = $_SESSION["uid"] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_note'])) {
  session_start();
  $uid = $_SESSION['uid'] ?? null;

  if (!$uid) {
    die('Unauthorized access');
  }

  $title = $_POST['title'] ?? '';
  $content = $_POST['content'] ?? '';
  $alarm = $_POST['alarm'] ?? null;
  $noteId = $_POST['note_id'] ?? null;

  $result = saveNote($uid, $title, $content, $alarm, $noteId);

  if (!$result['success']) {
    echo "<script>alert('" . $result['message'] . "');</script>";
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note_id'])) {
  session_start();
  $uid = $_SESSION['uid'] ?? null;

  if (!$uid) {
    die('Unauthorized access');
  }

  $noteId = intval($_POST['delete_note_id']);

  $result = deleteNote($uid, $noteId);

  if (!$result['success']) {
    echo "<script>alert('" . addslashes($result['message']) . "');</script>";
  } else {
    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }
}



$userNotes = getAllUserNotes($_SESSION['uid']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manager Reminders</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="./assets/js/main/clock.js" defer></script>
</head>

<body class="manager">


  <div class="top-banner" style="
  background: url('./assets/img/banners/19.jpg') no-repeat center center / cover;
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
    <!-- Sidebar -->
    <?php include "./php/includes/mgr_sidebar.php"; ?>

    <!-- Main Content -->
    <main class="mgr-main-content">
      <header class="mgr-header">
        <div>
          <h1>Welcome, <?= $userFullname ?></h1>
          <p class="emp-id"><b>Employee</b> || ID No.: <?= str_pad($userId, 4, '0', STR_PAD_LEFT) ?>
          </p>
        </div>
        <div class="mgr-branch-info">
          <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
          <span id="clock">--:--:--</span>
        </div>
      </header>

      <div class="mgr-search-filter">
        <input type="text" id="searchBar" placeholder="Search a note" onkeyup="searchTasks()" />

        <div class="mgr-title">
          <h1><br>Personal Reminders</h1><br>
          <p>Jot down your personal notes, meetings, to-do lists here!</p><br><br><br>
        </div>

        <form method="POST" class="mgr-notes-modal" id="mgr-notes-modal">
          <div class="mgr-notes-modal-content">
            <input type="hidden" name="note_id" id="note-id" />

            <input type="text" name="title" id="mgr-notes-title" class="mgr-notes-title-input"
              placeholder="Enter note title..." required />

            <textarea name="content" id="mgr-notes-text" placeholder="Write your note here..." required></textarea>

            <div class="mgr-notes-alarm">
              <label for="alarm-datetime">Set Notification:</label>
              <input type="datetime-local" name="alarm" id="alarm-datetime">
              <button type="button" onclick="setReminderNotification()">Set Alarm</button>
            </div>

            <div class="mgr-notes-modal-actions">
              <button class="mgr-notes-save" type="submit" name="save_note">Save</button>
              <button class="mgr-notes-cancel" type="button" onclick="mgrNotesCloseModal()">Cancel</button>
            </div>
          </div>
        </form>

        <main class="mgr-notes-section">
          <h1 class="mgr-notes-section-title">Reminders & Notes</h1>
          <div class="mgr-notes-note-grid" id="mgr-notes-grid">
            <?php foreach ($userNotes as $note): ?>
              <div class="mgr-notes-card" data-id="<?= $note['id'] ?>"
                data-title="<?= htmlspecialchars($note['title']) ?>"
                data-content="<?= htmlspecialchars($note['content']) ?>" data-alarm="<?= $note['alarm_time'] ?>"
                onclick="mgrNotesOpenModal(this)">

                <form method="POST" style="display:inline; float: right;">
                  <input type="hidden" name="delete_note_id" value="<?= $note['id'] ?>">
                  <button type="button" class="mgr-notes-delete-btn"
                    onclick="openDeleteModal(this, event)">Delete</button>
                </form>

                <h3><?= htmlspecialchars($note['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($note['content'])) ?></p>
                <?php if (!empty($note['alarm_time'])): ?>
                  <small>⏰ <?= date('M d, Y H:i', strtotime($note['alarm_time'])) ?></small>
                <?php endif; ?>
                <!-- Delete form -->
              </div>
            <?php endforeach; ?>
            <div class="mgr-notes-card mgr-notes-add-note" onclick="mgrNotesOpenModal()">+ Add Note</div>

          </div>
        </main>
        <div class="mgr-notes-confirm-modal" id="mgr-notes-delete-modal">
          <div class="mgr-notes-modal-content">
            <p style="font-size: 16px; margin-bottom: 16px;">
              Are you sure you want to delete this note? This action cannot be undone.
            </p>
            <div class="mgr-notes-modal-actions">
              <button class="mgr-notes-save" id="confirm-delete-btn">Yes, Delete</button>
              <button class="mgr-notes-cancel" onclick="closeDeleteModal()">Cancel</button>
            </div>
          </div>
        </div>



        <script>
          // Function to update clock

          const titleInput = document.getElementById('mgr-notes-title');

          let mode = 'note';
          let editing = false;
          let editTarget = null;

          const modal = document.getElementById('mgr-notes-modal');
          const confirmModal = document.getElementById('mgr-notes-confirm-modal');
          const viewModal = document.getElementById('mgr-notes-view-modal');
          const textArea = document.getElementById('mgr-notes-text');
          const notesGrid = document.getElementById('mgr-notes-grid');
          const viewContent = document.getElementById('mgr-notes-view-content');

          function mgrNotesOpenModal() {
            modal.style.display = 'flex';
            titleInput.value = '';
            textArea.value = '';
            textArea.classList.remove('hidden');
            editing = false;
            editTarget = null;
            mode = 'note';
          }

          function mgrNotesCloseModal() {
            modal.style.display = 'none';
          }

          function mgrNotesSetMode(selected) {
            mode = selected;
            if (selected === 'note') {
              textArea.classList.remove('hidden');
            } else {
              textArea.classList.add('hidden');
            }
          }

          function toggleCheckbox(checkbox) {
            const span = checkbox.nextElementSibling;
            if (checkbox.checked) {
              span.style.textDecoration = 'line-through';
              span.style.color = '#777';
            } else {
              span.style.textDecoration = 'none';
              span.style.color = 'initial';
            }
          }


          function mgrNotesPromptDraft() {
            confirmModal.style.display = 'flex';
          }

          function mgrNotesCloseConfirm() {
            confirmModal.style.display = 'none';
          }

          function mgrNotesSaveNote() {
            let content = '';
            const title = titleInput.value.trim();

            if (!title) return alert('Please enter a title');

            if (mode === 'note') {
              content = `<h3>${title}</h3><p>${textArea.value.trim()}</p>`;
              if (!textArea.value.trim()) return alert('Note content is empty');
            } else {
              const items = checklist.querySelectorAll('span');
              content = `<h3>${title}</h3><ul>`;
              items.forEach(item => {
                const val = item.innerText.trim();
                if (val) content += `<li>${val}</li>`;
              });
              content += '</ul>';
            }

            if (editing && editTarget) {
              editTarget.innerHTML = content;
            } else {
              const card = document.createElement('div');
              card.className = 'mgr-notes-card';
              card.innerHTML = content;
              card.onclick = () => mgrNotesViewNote(card);
              notesGrid.appendChild(card);
            }

            confirmModal.style.display = 'none';
            modal.style.display = 'none';
          }

          function mgrNotesViewNote(card) {
            viewModal.style.display = 'flex';
            viewContent.innerHTML = card.innerHTML;
            editTarget = card;
          }

          function mgrNotesEditFromView() {
            editing = true;
            modal.style.display = 'flex';
            viewModal.style.display = 'none';

            const parser = new DOMParser();
            const doc = parser.parseFromString(editTarget.innerHTML, 'text/html');
            const title = doc.querySelector('h3')?.textContent || '';
            titleInput.value = title;

            if (editTarget.innerHTML.includes('<ul>')) {
              mgrNotesSetMode('checklist');
              checklist.innerHTML = '';
              doc.querySelectorAll('li').forEach(li => addChecklistItem(li.textContent));
            } else {
              mgrNotesSetMode('note');
              textArea.value = doc.querySelector('p')?.textContent || '';
            }
          }

          function setReminderNotification() {
            const datetimeInput = document.getElementById('alarm-datetime');
            const timeString = datetimeInput.value;
            const title = titleInput.value.trim();

            if (!timeString) return alert('Please select a date and time for the alarm.');
            if (!title) return alert('Please enter a note title first.');

            const targetTime = new Date(timeString).getTime();
            const now = new Date().getTime();
            const delay = targetTime - now;

            if (delay <= 0) return alert('Selected time must be in the future.');

            Notification.requestPermission().then(permission => {
              if (permission === "granted") {
                setTimeout(() => {
                  new Notification("⏰ Reminder: " + title, {
                    body: "It's time to check your note: " + title,
                    icon: 'assets/svg/reminders.svg'
                  });
                }, delay);
                alert("Notification alarm set!");
              } else {
                alert("Notification permission denied.");
              }
            });
          }

          const deleteModal = document.getElementById('mgr-notes-delete-modal');

          let deleteFormToSubmit = null;

          function openDeleteModal(button, event) {
            event.stopPropagation();
            deleteModal.style.display = 'flex';
            deleteFormToSubmit = button.closest('form');
          }

          function closeDeleteModal() {
            deleteModal.style.display = 'none';
            deleteFormToSubmit = null;
          }

          function confirmDeleteNote() {
            if (deleteFormToSubmit) {
              deleteFormToSubmit.submit();
            }
          }

          document.getElementById('confirm-delete-btn').addEventListener('click', confirmDeleteNote);



          function mgrNotesCloseView() {
            viewModal.style.display = 'none';
          }


        </script>
</body>

</html>