document.addEventListener('DOMContentLoaded', function () {
    const inputAddress = document.getElementById('rc-ida-address');
    const suggestions = document.getElementById('rc-ida-results');

    // Show suggestions on inputAddress focus and hide on blur
    if (inputAddress) {
        inputAddress.addEventListener('focus', function () {
            suggestions.classList.remove('d-none');
        });

        // Filter suggestions based on keyword typed
        inputAddress.addEventListener('keyup', function () {
            suggestions.classList.remove('d-none');
            const query = inputAddress.value;

            // Filter the list items based on the query
            const items = suggestions.querySelectorAll('li');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const lowerCaseQuery = query.toLowerCase();

                if (text.includes(lowerCaseQuery)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // If the input is empty, hide the suggestions
            if (query.length < 1) {
                suggestions.classList.add('d-none');
            } else {
                suggestions.classList.remove('d-none');
            }
        });

        // Add click event listener to each suggestion list item
        suggestions.addEventListener('click', function (event) {
            if (event.target.tagName === 'LI') {
                suggestions.classList.add('d-none');
                const url = event.target.getAttribute('data-url');

                if (url) {
                    window.location.href = url;
                }
            }

        });
    }

});
