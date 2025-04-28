
let maleNames = [];
let femaleNames = [];

async function loadNames() {
  const maleData = await fetch('male_names.txt').then(res => res.text());
  const femaleData = await fetch('female_names.txt').then(res => res.text());
  maleNames = maleData.split('\n').filter(Boolean);
  femaleNames = femaleData.split('\n').filter(Boolean);
}

loadNames();

document.getElementById('generateBtn').addEventListener('click', () => {
  const gender = document.querySelector('input[name="gender"]:checked').value;
  const namesList = gender === 'male' ? maleNames : femaleNames;
  const randomName = namesList[Math.floor(Math.random() * namesList.length)];
  document.getElementById('nameResult').textContent = randomName;
});

document.getElementById('copyBtn').addEventListener('click', () => {
  const text = document.getElementById('nameResult').textContent;
  navigator.clipboard.writeText(text).then(() => {
    alert('Name copied: ' + text);
  });
});
