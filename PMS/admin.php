<form id="addSlotForm">
    <label for="slotID">New Slot ID:</label>
    <input type="text" name="slotID" required>
    <button type="submit">Add Slot</button>
</form>

<script>
document.getElementById("addSlotForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);

    fetch("add_slot.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || data.error);
        location.reload();
    })
    .catch(error => console.error("Error:", error));
});
</script>
