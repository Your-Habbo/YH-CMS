document.addEventListener('DOMContentLoaded', (event) => {
    const menuButton = document.getElementById('menu-button');
    const menuScreen = document.getElementById('menu-screen');
    const mainScreen = document.getElementById('screen');
    const clickWheel = document.getElementById('click-wheel');
    const ipod = document.getElementById('ipod');
    const extrasScreen = document.getElementById('extras-screen');
    const centerButton = document.getElementById('center-button');
    const volumeDisplay = document.getElementById('volume-display');
    const volumeLevel = document.getElementById('volume-level');
    let currentMenuIndex = 0;
    let currentScreen = mainScreen;
    let volume = 50; // Initial volume level

    menuButton.addEventListener('click', openMenu);
    centerButton.addEventListener('click', goBack);
    clickWheel.addEventListener('click', handleClickWheelClick);

    menuScreen.addEventListener('click', (e) => {
        const menuItem = e.target.closest('li');
        if (menuItem) {
            const menuItemText = menuItem.textContent.trim();
            handleMenuSelection(menuItemText);
        }
    });

    function handleClickWheelClick(e) {
        if (e.target.id === 'next-button') {
            adjustVolume(1);
        } else if (e.target.id === 'prev-button') {
            adjustVolume(-1);
        } else if (menuScreen.classList.contains('open')) {
            if (e.target.id === 'play-button') {
                const selectedItem = menuScreen.querySelectorAll('li')[currentMenuIndex];
                handleMenuSelection(selectedItem.textContent.trim());
            }
        }
    }

    function adjustVolume(change) {
        volume = Math.max(0, Math.min(100, volume + change * 5));
        volumeLevel.style.width = `${volume}%`;
        showVolume();
    }

    function showVolume() {
        volumeDisplay.classList.remove('hidden');
        volumeDisplay.classList.add('show');
        setTimeout(() => {
            volumeDisplay.classList.remove('show');
            volumeDisplay.classList.add('hidden');
        }, 1000);
    }

    function navigateMenu(direction) {
        const menuItems = menuScreen.querySelectorAll('li');
        menuItems[currentMenuIndex].classList.remove('selected');
        currentMenuIndex = (currentMenuIndex + direction + menuItems.length) % menuItems.length;
        menuItems[currentMenuIndex].classList.add('selected');
    }

    function handleMenuSelection(menuItemText) {
        switch(menuItemText) {
            case 'Now Playing':
                showScreen(mainScreen);
                break;
            case 'Extras':
                showScreen(extrasScreen);
                break;
        }
        menuScreen.classList.remove('open');
    }

    function openMenu(e) {
        e.preventDefault();
        e.stopPropagation();
        menuScreen.classList.add('open');
        // Reset menu selection when opening
        const menuItems = menuScreen.querySelectorAll('li');
        menuItems.forEach((item, index) => {
            if (index === 0) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
        currentMenuIndex = 0;
    }

    function goBack() {
        if (menuScreen.classList.contains('open')) {
            menuScreen.classList.remove('open');
        } else if (currentScreen !== mainScreen) {
            showScreen(mainScreen);
        }
    }

    function showScreen(screen) {
        const screens = [menuScreen, mainScreen, extrasScreen];
        screens.forEach(s => {
            if (s === screen) {
                s.style.display = 'block';
                currentScreen = s;
            } else {
                s.style.display = 'none';
            }
        });
    }
});