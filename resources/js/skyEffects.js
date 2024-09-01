// resources/js/skyEffects.js

export function initializeSkyEffects() {

// Constants for color values
const NIGHT_SKY = [25, 25, 112];     // Midnight blue
const SUNRISE_SKY = [255, 192, 203]; // Pink
const DAY_SKY = [135, 206, 235];     // Light blue
const SUNSET_SKY = [255, 179, 71];   // Orange

function lerp(start, end, t) {
    return start.map((startValue, i) => Math.round(startValue + t * (end[i] - startValue)));
}

function getTimeOfDay(date) {
    const hours = date.getHours();
    const minutes = date.getMinutes();
    return hours + minutes / 60;
}

function getSkyGradient(timeOfDay) {
    let topColor, bottomColor, progress;
    
    if (timeOfDay >= 0 && timeOfDay < 5) { // Night
        topColor = bottomColor = NIGHT_SKY;
    } else if (timeOfDay >= 5 && timeOfDay < 7) { // Sunrise
        progress = (timeOfDay - 5) / 2;
        topColor = lerp(NIGHT_SKY, SUNRISE_SKY, progress);
        bottomColor = lerp(NIGHT_SKY, DAY_SKY, progress);
    } else if (timeOfDay >= 7 && timeOfDay < 18) { // Day
        topColor = bottomColor = DAY_SKY;
    } else if (timeOfDay >= 18 && timeOfDay < 20) { // Sunset
        progress = (timeOfDay - 18) / 2;
        topColor = lerp(DAY_SKY, SUNSET_SKY, progress);
        bottomColor = lerp(DAY_SKY, NIGHT_SKY, progress);
    } else { // Back to night
        progress = (timeOfDay - 20) / 4;
        topColor = lerp(SUNSET_SKY, NIGHT_SKY, progress);
        bottomColor = NIGHT_SKY;
    }
    
    return {
        top: `rgb(${topColor[0]}, ${topColor[1]}, ${topColor[2]})`,
        bottom: `rgb(${bottomColor[0]}, ${bottomColor[1]}, ${bottomColor[2]})`
    };
}

function updateSkyBackground(customTime = null) {
    const now = customTime ? new Date(customTime) : new Date();
    const timeOfDay = getTimeOfDay(now);
    const { top, bottom } = getSkyGradient(timeOfDay);
    
    const header = document.querySelector('header');
    header.style.background = `linear-gradient(to bottom, ${top}, ${bottom})`;
}

// Update every second
setInterval(updateSkyBackground, 1000);

// Initial update
updateSkyBackground();

// For debugging: updateSkyBackground('2023-04-15T12:00:00'); // Set custom time

updateSkyBackground('2023-04-15T21:00:00');

}
 