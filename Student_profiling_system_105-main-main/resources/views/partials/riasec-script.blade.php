<script>
const questions = [
    { text: "I like to work on cars", value: "R" },
    { text: "I like to do puzzles", value: "I" },
    { text: "I am good at working independently", value: "A" },
    { text: "I like to work in teams", value: "S" },
    { text: "I am an ambitious person, I set goals for myself", value: "E" },
    { text: "I like to organize things, (files, desks/offices)", value: "C" },
    { text: "I like to build things", value: "R" },
    { text: "I like to read about art and music", value: "A" },
    { text: "I like to have clear instructions to follow", value: "C" },
    { text: "I like to try to influence or persuade people", value: "E" },
    { text: "I like to do experiments", value: "I" },
    { text: "I like to teach or train people", value: "S" },
    { text: "I like trying to help people solve their problems", value: "S" },
    { text: "I like to take care of animals", value: "R" },
    { text: "I wouldn't mind working 8 hours per day in an office", value: "C" },
    { text: "I like selling things", value: "E" },
    { text: "I enjoy creative writing", value: "A" },
    { text: "I enjoy science", value: "I" },
    { text: "I am quick to take on new responsibilities", value: "E" },
    { text: "I am interested in healing people", value: "S" },
    { text: "I enjoy trying to figure out how things work", value: "I" },
    { text: "I like putting things together or assembling things", value: "R" },
    { text: "I am a creative person", value: "A" },
    { text: "I pay attention to details", value: "C" },
    { text: "I like to do filing or typing", value: "C" },
    { text: "I like to analyze things (problems/situations)", value: "I" },
    { text: "I like to play instruments or sing", value: "A" },
    { text: "I enjoy learning about other cultures", value: "S" },
    { text: "I would like to start my own business", value: "E" },
    { text: "I like to cook", value: "R" },
    { text: "I like acting in plays", value: "A" },
    { text: "I am a practical person", value: "R" },
    { text: "I like working with numbers or charts", value: "I" },
    { text: "I like to get into discussions about issues", value: "S" },
    { text: "I am good at keeping records of my work", value: "C" },
    { text: "I like to lead", value: "E" },
    { text: "I like working outdoors", value: "R" },
    { text: "I would like to work in an office", value: "C" },
    { text: "I'm good at math", value: "I" },
    { text: "I like helping people", value: "S" },
    { text: "I like to draw", value: "A" },
    { text: "I like to give speeches", value: "E" }

];

let answers = {};
const questionContainer = document.getElementById("questionContainer");

document.getElementById('startBtn').addEventListener('click', function () {
    document.getElementById('instructionSection').style.display = 'none';
    const resultSection = document.getElementById('resultSection');
    if (resultSection) resultSection.style.display = 'none';
    document.getElementById('riasecForm').style.display = 'block';
    renderAllQuestions();
});

let currentQuestionNum = 1;
let maxReachedQuestion = 1;

function renderAllQuestions() {
    // Clear container
    questionContainer.innerHTML = '';


    // Render all questions
    questions.forEach((q, index) => {
        const num = index + 1;
        const answer = answers[num] || null;

        const card = document.createElement("div");
        card.className = "question-card";
        card.setAttribute('data-question', num);
        card.innerHTML = `
            <div class="question-content">
                <div class="question-number">Question ${num} of ${questions.length}</div>
                <p>${q.text}</p>
                <div class="yesno-group">
                    <label class="yesno-option ${answer === 'yes' ? 'selected' : ''}">
                        <input type="radio" name="answers[${num}]" value="yes"
                               ${answer === 'yes' ? 'checked' : ''}
                               onchange="saveAnswer(${num}, 'yes', '${q.value}')">
                        Yes
                    </label>
                    <label class="yesno-option ${answer === 'no' ? 'selected' : ''}">
                        <input type="radio" name="answers[${num}]" value="no"
                               ${answer === 'no' ? 'checked' : ''}
                               onchange="saveAnswer(${num}, 'no', '${q.value}')">
                        No
                    </label>
                </div>
            </div>
        `;
        questionContainer.appendChild(card);
    });

    // Submit button will be added dynamically when all questions are answered

    // Update progress on scroll and blur effect, with scroll restriction
    questionContainer.addEventListener('scroll', restrictScrolling);

    // Initial blur effect
    setTimeout(() => {
        const firstCard = document.querySelector('.question-card[data-question="1"]');
        if (firstCard) {
            firstCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            updateBlurEffect();
        }
    }, 100);

    // Check if submit button should be enabled
    updateSubmitButton();
}

function restrictScrolling() {
    updateProgressOnScroll();
    updateBlurEffect();
    
    const container = questionContainer;
    const containerRect = container.getBoundingClientRect();
    const containerCenter = containerRect.top + containerRect.height / 1;
    
    const cards = document.querySelectorAll('.question-card');
    cards.forEach(card => {
        const cardNum = parseInt(card.getAttribute('data-question'));
        const cardRect = card.getBoundingClientRect();
        const cardCenter = cardRect.top + cardRect.height / 1;
        const distance = Math.abs(cardCenter - containerCenter);
        
        // Check if user is trying to scroll past unanswered questions
        if (distance < cardRect.height / 1) {
            currentQuestionNum = cardNum;
            
            // If user scrolled to a question beyond what they've answered
            if (cardNum > maxReachedQuestion) {
                // Scroll back to the last answered question
                const lastAnsweredCard = document.querySelector(`.question-card[data-question="${maxReachedQuestion}"]`);
                if (lastAnsweredCard) {
                    lastAnsweredCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    });
}

function updateProgressOnScroll() {
    const answeredCount = Object.keys(answers).length;
    const progress = (answeredCount / questions.length) * 100;
    document.querySelector('.progress-bar').style.width = `${progress}%`;
}

function updateSubmitButton() {
    const answeredCount = Object.keys(answers).length;
    let submitSection = document.getElementById('submitSection');
    if (answeredCount === questions.length) {
        if (!submitSection) {
            submitSection = document.createElement("div");
            submitSection.id = "submitSection";
            submitSection.className = "submit-section fixed-submit";
            submitSection.innerHTML = `
                <button type="button" id="submitBtn" class="btn btn-primary" onclick="submitForm()">
                    Submit Test
                </button>
            `;
            document.body.appendChild(submitSection);
        }
    } else {
        if (submitSection) {
            document.body.removeChild(submitSection);
        }
    }
}

function saveAnswer(num, choice, value) {
    answers[num] = choice === 'yes' ? value : null;
    localStorage.setItem("answers", JSON.stringify(answers));

    // Update max reached question to allow scrolling to next question
    if (num >= maxReachedQuestion) {
        maxReachedQuestion = num + 1;
    }

    // Update the visual selection
    const card = document.querySelector(`.question-card[data-question="${num}"]`);
    if (card) {
        const options = card.querySelectorAll('.yesno-option');
        options.forEach(opt => opt.classList.remove('selected'));

        const selectedOption = card.querySelector(`input[value="${choice}"]`).parentElement;
        selectedOption.classList.add('selected');
    }

    // Update progress
    updateProgressOnScroll();

    // Check if submit button should be enabled
    updateSubmitButton();

    // Auto-scroll to next question
    if (num < questions.length) {
        const nextCard = document.querySelector(`.question-card[data-question="${num + 1}"]`);
        if (nextCard) {
            setTimeout(() => {
                nextCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                updateBlurEffect();
            }, 300);
        }
    }
}

function updateBlurEffect() {
    const container = questionContainer;
    const containerRect = container.getBoundingClientRect();
    const containerCenter = containerRect.top + containerRect.height / 2;
    
    const cards = document.querySelectorAll('.question-card');
    cards.forEach(card => {
        const cardRect = card.getBoundingClientRect();
        const cardCenter = cardRect.top + cardRect.height / 2;
        const distance = Math.abs(cardCenter - containerCenter);
        
        // Card is centered if distance is less than half its height
        if (distance < cardRect.height / 2) {
            card.classList.remove('blurred');
            card.classList.add('focused');
        } else {
            card.classList.remove('focused');
            card.classList.add('blurred');
        }
    });
}

function submitForm() {
    let counts = { R:0, I:0, A:0, S:0, E:0, C:0 };
    Object.values(answers).forEach(v => {
        if (v) counts[v]++;
    });

    const totalResponses = Object.values(counts).reduce((a, b) => a + b, 0);
    
    let code;
    if (totalResponses === 0) {
        alert("Please answer at least one question with 'Yes' to get meaningful results.");
        return;
    } else {
        let sorted = Object.entries(counts).sort((a, b) => b[1] - a[1]);
        let top3 = sorted.slice(0, 3);
        code = top3.map(([letter]) => letter).join("");
    }

    // Send to backend
    fetch("{{ route('riasec.save') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            code: code,
            scores: counts
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        }
    })
    .catch(err => console.error(err));
}

document.addEventListener("DOMContentLoaded", () => {
    localStorage.removeItem("answers");
    answers = {};
});
</script>


<style>
#questionContainer {
    max-height: 400px;
    overflow-y: auto;
    padding: 20px;
    margin: 20px 0;
    scroll-behavior: smooth;
}

.scroll-instruction {
    position: sticky;
    top: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    z-index: 10;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.scroll-instruction p {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.question-card {
    background: #f9fcff;
    border: 1px solid #d6ebf7;
    border-radius: 12px;
    padding: 20px 25px;
    margin-bottom: 20px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    position: relative;
    transition: all 0.4s ease;
    filter: blur(0px);
    opacity: 0.4;
    transform: scale(0.95);
}

.question-card.focused {
    filter: blur(0px);
    opacity: 1;
    transform: scale(1);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border-color: #1cc2f2;
    background: linear-gradient(145deg, #ffffff, #f9fcff);
}

.question-card.blurred {
    filter: blur(3px);
    opacity: 0.4;
    transform: scale(0.95);
    pointer-events: none;
}

.question-card.focused:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 28px rgba(0,0,0,0.15);
}

.question-number {
    font-size: 12px;
    color: #0089b7;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.question-card p {
    font-size: 1.4rem;
    color: #0f172a;
    margin-bottom: 20px;
    line-height: 1.6;
    font-weight: 600;
    background: linear-gradient(145deg, #ffffff, #f1f5f9);
    padding: 18px 22px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.yesno-group {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 10px;
}

.yesno-option {
    flex: 1;
    text-align: center;
    background: #fff;
    border: 2px solid #d6ebf7;
    border-radius: 10px;
    padding: 12px 0;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease-in-out;
    font-size: 16px;
    position: relative;
    overflow: hidden;
}

.yesno-option:first-child:hover {
    background: #e7f9e7;
    color: #2e7031;
    border-color: #4caf50;
}

.yesno-option:first-child.selected {
    background: #4caf50;
    color: white;
    border-color: #4caf50;
}

.yesno-option:last-child:hover {
    background: #ffebee;
    color: #c62828;
    border-color: #ef5350;
}

.yesno-option:last-child.selected {
    background: #ef5350;
    color: white;
    border-color: #ef5350;
}

.yesno-option input {
    display: none;
}

.yesno-option:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.submit-section {
    text-align: center;
    padding: 30px 0;
    margin-top: 20px;
}

#submitBtn {
    background: #1cc2f2;
    color: white;
    border: none;
    padding: 14px 40px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(28, 194, 242, 0.3);
}

#submitBtn:hover:not(.disabled) {
    background: #0ca6d4;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(28, 194, 242, 0.4);
}

#submitBtn.disabled {
    background: #cccccc;
    color: #666666;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.fixed-submit {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    padding: 20px;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
    z-index: 1000;
    text-align: center;
}

@media (max-width: 768px) {
    .fixed-submit {
        padding: 15px;
    }
}

#questionContainer {
    overflow-y: scroll;        
    scrollbar-width: none;      
}

#questionContainer::-webkit-scrollbar {
    display: none;              
}

</style>