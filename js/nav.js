document.addEventListener('DOMContentLoaded', function () {
    fetch('./nav.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('nav-container').innerHTML = data;

            const currentPage = window.location.pathname.split('/').pop();

            const navItems = document.querySelectorAll(".nav-item");

            navItems.forEach(item => {
                const linkItem = item.querySelector("a").getAttribute("href");
                if (linkItem === currentPage) {
                    item.classList.add('active');
                }
                item.addEventListener('click', function () {
                    navItems.forEach(navItem => navItem.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
});     