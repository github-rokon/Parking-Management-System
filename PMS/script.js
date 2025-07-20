document.getElementById("bookingForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);
    fetch("book_slot.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Booking Confirmed!\nUser ID: ${data.userID}\nSlot ID: ${data.slotID}`);
            document.querySelector(`.slot[data-slot="${data.slotID}"]`).classList.add("booked");
            closePanel();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
});
function searchBookings() {
    let phone = document.getElementById("phone").value.trim();
    
    if (phone === "") {
        alert("Please enter your phone number.");
        return;
    }

    fetch(`search_bookings.php?phone=${phone}`)
        .then(response => response.json())
        .then(data => {
            let tbody = document.getElementById("bookingResults");
            tbody.innerHTML = "";

            if (data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='7'>No bookings found.</td></tr>";
                return;
            }

            data.forEach(row => {
                let tr = document.createElement("tr");
                tr.innerHTML = `<td>${row.BOOKING_ID}</td>
                                <td>${row.USER_ID}</td>
                                <td>${row.VEHICLE_ID}</td>
                                <td>${row.SLOT_ID}</td>
                                <td>${row.BOOKING_DATE}</td>
                                <td>${row.DURATION}</td>
                                <td>${row.STATUS}</td>`;
                tbody.appendChild(tr);
            });
        })
        .catch(error => console.error("Error fetching bookings:", error));
}
