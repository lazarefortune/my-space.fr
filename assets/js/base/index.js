import {createIcons, icons} from 'lucide';

createIcons({icons});


document.addEventListener('DOMContentLoaded', () => {
    // user dropdown
    const userDropdownIcon = document.querySelector('#user-dropdown-icon');
    const userDropdown = document.querySelector('#user-dropdown');

    userDropdownIcon.addEventListener('click', () => {
        userDropdown.classList.toggle('hidden');
    });

    // click outside to close dropdown
    document.addEventListener('click', (e) => {
        if (!userDropdownIcon.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });
});
