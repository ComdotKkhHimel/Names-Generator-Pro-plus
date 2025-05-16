const fs = require('fs');

// Base name lists (extend with more names or load from datasets)
const maleFirstNames = [
    'James', 'Michael', 'William', 'David', 'John', 'Robert', 'Thomas', 'Charles', 'Christopher', 'Daniel',
    'Matthew', 'Andrew', 'Joseph', 'Mark', 'Paul', 'Steven', 'Richard', 'Edward', 'George', 'Benjamin',
    // Add more or load from a source
];
const femaleFirstNames = [
    'Mary', 'Patricia', 'Jennifer', 'Linda', 'Elizabeth', 'Barbara', 'Susan', 'Jessica', 'Sarah', 'Karen',
    'Nancy', 'Lisa', 'Margaret', 'Ashley', 'Dorothy', 'Kimberly', 'Emily', 'Donna', 'Michelle', 'Carol',
    // Add more or load from a source
];
const lastNames = [
    'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
    'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
    // Add more or load from a source
];

// Function to generate 1,000 names
function generateNameList(baseNames, count) {
    const result = [...baseNames];
    let index = 1;
    while (result.length < count) {
        const baseName = baseNames[Math.floor(Math.random() * baseNames.length)];
        result.push(`${baseName}${index}`);
        index++;
    }
    return result.slice(0, count);
}

// Generate and save 1,000 names
fs.writeFileSync('male_names.txt', generateNameList(maleFirstNames, 1000).join('\n'));
fs.writeFileSync('female_names.txt', generateNameList(femaleFirstNames, 1000).join('\n'));
fs.writeFileSync('last_names.txt', generateNameList(lastNames, 1000).join('\n'));

console.log('Generated 1,000 names for each file.');