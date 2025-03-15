document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.course').forEach(course => {
        course.addEventListener('click', function(event) {
            if (event.target.closest('.book')) return;
            if (event.target.closest('.staff-email')) return;
            const courseDetails = this.querySelector('.course-details');
            document.querySelectorAll('.course-details.open').forEach(details => {
                if (details !== courseDetails) {
                    details.classList.remove('open');
                }
            });
            courseDetails.classList.toggle('open');
        });
    });
});