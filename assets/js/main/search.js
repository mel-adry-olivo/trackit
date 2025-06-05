function searchTasks() {
  const input = document.getElementById("searchBar").value.toLowerCase();
  const rows = document.querySelectorAll("#taskBody tr");

  rows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    const rowText = Array.from(cells)
      .map((td) => td.textContent.toLowerCase())
      .join(" ");

    if (rowText.includes(input)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}
