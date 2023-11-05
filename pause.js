special_terms = [
  "automata",
  "deterministic program",
  "delta",
  "cartesian product",
  "tollgate",
  "delta",
  "start state",
  "binary string",
  "reject state",
  "transition diagram",
  "table method",
  "transition function",
  "graphical representation",
  "graph theory",
  "automaton",
  "automata",
  "finite state automata",
  "transition system",
  "state transition",
  "memory",
  "language",
  "mathematically perceived",
  "string",
  "graph theory",
  "initial state",
  "start state",
  "accept state",
  "final state",
  "formal definition",
  "alphabet",
  "symbols",
  "function",
  "cartesian product",
  "transition function",
  "start state",
  "binary string",
  "state diagram",
  "state table",
  "graphical representation",
  "substring",
  "sequence number",
  "consecutive symbols",
  "accept string",
  "labeled transitions",
  "accept states",
  "reject states",
  "accept state circle",
  "previous example",
]

function presentQuestion(sentence) {
  // Select a random word from the sentence
  sentence = sentence.toLowerCase();
  const words = sentence.split(" ");
  
  const randomIndex = Math.floor(Math.random() * words.length);
 
  const specialTerm = special_terms.find((term) => sentence.includes(term));
  if(!specialTerm) return;
  const blankWord = specialTerm;
  console.log(specialTerm);
  const specialTermIndex = sentence.indexOf(specialTerm);
  const specialTermLength = specialTerm.length;
  const blankCharacters = "_".repeat(specialTermLength);
  const modifiedSentence = sentence.substring(0, specialTermIndex) + blankCharacters + sentence.substring(specialTermIndex + specialTermLength);
  const questionElement = document.createElement("p");
  
  questionElement.innerText = modifiedSentence;
  questionElement.className = "lead mt-3"; // Bootstrap class for larger text

  const formGroupElement = document.createElement("div");
  formGroupElement.className = "form-inline"; // Bootstrap class for inline forms

  const inputElement = document.createElement("input");
  inputElement.setAttribute("type", "text");
  inputElement.setAttribute("id", "answer");
  inputElement.className = "form-control form-control-sm mr-2"; // Bootstrap classes for small inputs and margin

  const buttonElement = document.createElement("button");
  buttonElement.innerText = "Submit Answer";
  buttonElement.className = "btn btn-primary"; // Bootstrap classes for buttons
  buttonElement.addEventListener("click", () => {
    const studentAnswer = document.getElementById("answer").value;
    if (studentAnswer === blankWord) {
      alert("Correct!");
    } else {
      alert(`Incorrect, the correct answer was ${blankWord}`);
    }
  });

  formGroupElement.appendChild(inputElement);
  formGroupElement.appendChild(buttonElement);

  // Append the elements to the div with the id "question"
  const questionDiv = document.getElementById("question");
  questionDiv.innerHTML = ""; // Clear any previous content
  questionDiv.appendChild(questionElement);
  questionDiv.appendChild(formGroupElement);
}



function getRandomSentence(start, end, vttFile) {
  // Split the VTT file into blocks
  const blocks = vttFile.split("\n\n");

  // Filter out the blocks that are within the start and end time range
  const validBlocks = blocks.filter((block) => {
    const times = block.split("\n")[0];
    const timeComponents = times.split(" --> ");

    // Continue if the block does not contain proper time markings
    if (timeComponents.length < 2) return false;

    const startBlock = parseFloat(
      timeComponents[0]
        .split(":")
        .reduce(
          (acc, time, i) => acc + parseFloat(time) * Math.pow(60, 2 - i),
          0
        )
    );
    const endBlock = parseFloat(
      timeComponents[1]
        .split(":")
        .reduce(
          (acc, time, i) => acc + parseFloat(time) * Math.pow(60, 2 - i),
          0
        )
    );

    return startBlock >= start && endBlock <= end;
  });

  // Check if there are valid blocks
  if (!validBlocks.length) {
    throw new Error("No sentences found within the given time range.");
  }

  console.log('validBlocks',validBlocks);
  // Select a random block that contains a special term
  const blocksWithSpecialTerm = validBlocks.filter((block) => {
    return special_terms.some(term => block.toLowerCase().includes(term));
  });

  console.log('blocksWithSpecialTerm',blocksWithSpecialTerm);

  if (!blocksWithSpecialTerm.length) {
    throw new Error("No sentences found within the given time range that contain a special term.");
  }

  const randomBlock = blocksWithSpecialTerm[Math.floor(Math.random() * blocksWithSpecialTerm.length)];
  // Return the sentence
  result =  randomBlock.split("\n").slice(1).join("\n");
  console.log('result', result);
  return result;
}

function fetchSubtitles(start, end, subtitleFile) {
  return new Promise((resolve, reject) => {
    fetch("get_subtitles.php?subtitleFile=" + subtitleFile)
      .then((response) => response.text())
      .then((data) => {
        const randomSentence = getRandomSentence(start, end, data);
        const hasSpecialTerm = special_terms.some(term => randomSentence.toLowerCase().includes(term));
      
        const foundSpecialTerms = special_terms.filter(term => randomSentence.toLowerCase().includes(term));
        console.log("foundSpecialTerms: ", foundSpecialTerms);

        
        if (!hasSpecialTerm) {
          resolve(null);
        }
        console.log("randomSentence: ", randomSentence);
        presentQuestion(randomSentence);
        resolve(randomSentence);
      })
      .catch((error) => {
        console.log("error: ", error);
        reject("");
      });
  });
}

function secondsToTime(seconds) {
  let hours = Math.floor(seconds / 3600);
  let minutes = Math.floor((seconds / 60) % 60);
  seconds = seconds % 60;
  const result = `${hours.toString().padStart(2, "0")}:${minutes
    .toString()
    .padStart(2, "0")}:${seconds.toFixed(3).padStart(6, "0")}`;
  console.log("result: ", result);
  return result;
}
