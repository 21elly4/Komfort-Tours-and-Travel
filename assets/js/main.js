document.addEventListener('DOMContentLoaded', () => {
    const yearNodes = document.querySelectorAll('.js-year');
    const year = new Date().getFullYear();
    yearNodes.forEach((node) => {
        node.textContent = year;
    });
});
