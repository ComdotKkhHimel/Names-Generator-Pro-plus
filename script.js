
let maleNames = [];
let femaleNames = [];
let usedMaleNames = new Set();
let usedFemaleNames = new Set();
let lastName = "";

function loadNames(file, targetArray) {
    return fetch(file)
        .then(response => response.text())
        .then(text => {
            targetArray.length = 0;
            targetArray.push(...text.trim().split('\n').filter(name => name));
        });
}

Promise.all([
    loadNames('male_names.txt', maleNames),
    loadNames('female_names.txt', femaleNames)
]).then(() => {
    console.log('Names loaded from text files');
});

function getUniqueName(namesArray, usedSet) {
    if (usedSet.size >= namesArray.length) {
        usedSet.clear(); // Reset if all names used
    }
    let name;
    let tries = 0;
    do {
        name = namesArray[Math.floor(Math.random() * namesArray.length)];
        tries++;
    } while (usedSet.has(name) && tries < 1000);
    usedSet.add(name);
    return name;
}

function generateNames(gender) {
    const nameBox = document.getElementById('generatedNameBox');
    nameBox.textContent = "Generating";
    let dots = 0;
    const loadingInterval = setInterval(() => {
        dots = (dots + 1) % 4;
        nameBox.textContent = "Generating" + ".".repeat(dots);
    }, 300);

    setTimeout(() => {
        clearInterval(loadingInterval);
        const namesArray = gender === 'male' ? maleNames : femaleNames;
        const usedSet = gender === 'male' ? usedMaleNames : usedFemaleNames;

        if (namesArray.length === 0) {
            nameBox.textContent = "Loading names...";
            return;
        }

        const name = getUniqueName(namesArray, usedSet);
        lastName = name;
        nameBox.textContent = name;
    }, 1000);
}

function copyToClipboard() {
    const nameBox = document.getElementById('generatedNameBox');
    const name = nameBox.textContent;
    navigator.clipboard.writeText(name);
    nameBox.classList.add("copied");
    setTimeout(() => {
        nameBox.classList.remove("copied");
    }, 500);
}

document.addEventListener('DOMContentLoaded', () => {
    const bg = document.getElementById('background');
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + 'vw';
        particle.style.top = Math.random() * 100 + 'vh';
        particle.style.animationDuration = (5 + Math.random() * 5) + 's';
        bg.appendChild(particle);
    }
});
