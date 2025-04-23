// Name pools
const maleNames = [
  "James", "John", "Robert", "Michael", "William",
  "David", "Richard", "Joseph", "Thomas", "Charles"
];

const femaleNames = [
  "Mary", "Patricia", "Jennifer", "Linda", "Elizabeth",
  "Barbara", "Susan", "Jessica", "Sarah", "Karen"
];

const lastNames = [
  "Smith", "Johnson", "Brown", "Taylor", "Anderson",
  "Thomas", "Jackson", "White", "Harris", "Martin"
];

let selectedGender = "male"; // Default gender

// Function to select gender
function selectGender(gender) {
  selectedGender = gender;

  // Toggle active state for gender buttons
  document.getElementById("maleButton").classList.remove("active");
  document.getElementById("femaleButton").classList.remove("active");

  if (gender === "male") {
    document.getElementById("maleButton").classList.add("active");
  } else {
    document.getElementById("femaleButton").classList.add("active");
  }
}

// Function to generate a random name
function generateName() {
  const firstNamePool = selectedGender === "male" ? maleNames : femaleNames;

  // Generate a random first name and last name
  const firstName = firstNamePool[Math.floor(Math.random() * firstNamePool.length)];
  const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];

  // Display the generated name
  const fullName = `${firstName} ${lastName}`;
  document.getElementById("nameDisplay").innerText = fullName;
}