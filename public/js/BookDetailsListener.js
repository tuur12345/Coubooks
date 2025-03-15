document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".book").forEach(book => {
        book.addEventListener("click", function (event) {
            const isbn = event.currentTarget.dataset.isbn;
            const detailsContainer = event.currentTarget.querySelector(".book-details");
            fetch(`https://openlibrary.org/isbn/${isbn}.json`)
                .then((response) => {if (response.ok) return response.json()})
                .then((json) => {
                    if (json && json.title) {
                        detailsContainer.innerHTML =
                            `<p><strong>Title:</strong> ${json.title}</p>
                                     <p><strong>Published:</strong> ${json.publish_date}</p>
                                     <p><strong>Pages:</strong> ${json.number_of_pages ? json.number_of_pages : "Not available"}</p>`
                        detailsContainer.classList.toggle('open');
                    } else {
                        detailsContainer.innerHTML = "<p>No additional information available.</p>";
                        detailsContainer.classList.toggle('error');
                    }
                });
        });
    });
});