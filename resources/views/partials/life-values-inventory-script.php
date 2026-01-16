
<script>
const questions = [
    { text: "Challenging myself to achieve", value: "A" },
    { text: "Being liked by others", value: "B" },
    { text: "Protecting the environment", value: "C" },
    { text: "Being sensitive to others’ needs", value: "D" },
    { text: "Coming up with new ideas", value: "E" },
    { text: "Having financial success", value: "F" },
    { text: "Taking care of my body", value: "G" },
    { text: "Downplaying compliments or praise", value: "H" },
    { text: "Being independent (doing things I want to do)", value: "I" },
    { text: "Accepting my place in my family or group", value: "J" },
    { text: "Having time to myself", value: "K" },
    { text: "Being reliable", value: "L" },
    { text: "Using science for progress", value: "M" },
    { text: "Believing in a higher power", value: "N" },
    { text: "Improving my performance", value: "A" },
    { text: "Being accepted by others", value: "B" },
    { text: "Taking care of the environment", value: "C" },
    { text: "Helping others", value: "D" },
    { text: "Creating new things or ideas", value: "E" },
    { text: "Making money", value: "F" },
    { text: "Being in good physical shape", value: "G" },
    { text: "Being quiet about my success", value: "H" },
    { text: "Giving my opinion", value: "I" },
    { text: "Respecting the traditions of my family or group", value: "J" },
    { text: "Having quiet time to think", value: "K" },
    { text: "Being trustworthy", value: "L" },
    { text: "Knowing things about science", value: "M" },
    { text: "Believing that there is something greater than ourselves", value: "N" },
    { text: "Working hard to do better", value: "A" },
    { text: "Feeling as though I belong", value: "B" },
    { text: "Appreciating the beauty of nature", value: "C" },
    { text: "Being concerned about the rights of others", value: "D" },
    { text: "Discovering new things or ideas", value: "E" },
    { text: "Being wealthy", value: "F" },
    { text: "Being strong or good in a sport", value: "G" },
    { text: "Avoiding credit for my accomplishments", value: "H" },
    { text: "Having control over my time", value: "I" },
    { text: "Making decisions with my family or group in mind", value: "J" },
    { text: "Having a private place to go", value: "K" },
    { text: "Meeting my obligations", value: "L" },
    { text: "Knowing about math", value: "M" },
    { text: "Living in harmony with my spiritual beliefs", value: "N" }
];

     const ratingLabels = {
    1: "Not Important at all",
    2: "Slightly Important",
    3: "Moderately Important",
    4: "Very Important",
    5: "Extremely Important"
};

let answers = {};
const questionContainer = document.getElementById("questionContainer");

let currentQuestionNum = 1;
let maxReachedQuestion = 1;

document.getElementById("startBtn").addEventListener("click", () => {
    document.getElementById("instructionSection").style.display = "none";
    document.getElementById("lifeValuesForm").style.display = "block";
    renderAllQuestions();
});

function renderAllQuestions() {

    // Render all questions
    questions.forEach((q, index) => {
        const num = index + 1;
        const answer = answers[num] || null;

        const card = document.createElement("div");
        card.className = "question-card";
        card.setAttribute('data-question', num);
        card.innerHTML = `
            <div class="question-content">
                <div class="question-number">Question</div>
                <p>${q.text}</p>
                <div class="radio-group">
                    ${[1,2,3,4,5].map(value => `
                        <div class="radio-option ${answer == value ? 'selected' : ''}">
                            <input type="radio" id="q${num}_${value}" name="q_${num}" value="${value}"
                                ${answer == value ? 'checked' : ''}
                                onchange="saveAnswer(${num}, ${value})">
                            <label for="q${num}_${value}">${value}</label>
                        </div>
                    `).join('')}
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


function saveAnswer(num, rating) {
    answers[num] = rating;
    localStorage.setItem("answers", JSON.stringify(answers));

    // Update max reached question to allow scrolling to next question
    if (num >= maxReachedQuestion) {
        maxReachedQuestion = num + 1;
    }

    // Update the visual selection
    const card = document.querySelector(`.question-card[data-question="${num}"]`);
    if (card) {
        const options = card.querySelectorAll('.radio-option');
        options.forEach(opt => opt.classList.remove('selected'));

        const selectedOption = card.querySelector(`input[value="${rating}"]`).parentElement;
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


    function updateScores() {
    const counts = { 
            A:0, B:0, C:0, D:0, E:0,
            F:0, G:0, H:0, I:0, J:0, K:0, L:0, M:0, N:0
        };

        Object.keys(answers).forEach(k => {
            const index = parseInt(k) - 1;
            const key = questions[index].value; 
            counts[key] += parseInt(answers[k]);
        });

        Object.keys(counts).forEach(key => {
            const scoreElem = document.getElementById(`score_${key}`);
            if (scoreElem) {
                scoreElem.textContent = counts[key];
            }
        });

        localStorage.setItem("scores", JSON.stringify(counts));
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

    // Submit Form
    function submitForm() {
        let counts = { A:0, B:0, C:0, D:0, E:0, F:0, G:0, H:0, I:0, J:0, K:0, L:0, M:0, N:0 };
        Object.keys(answers).forEach(k => {
            const index = parseInt(k) - 1;
            const key = questions[index].value;
            counts[key] += parseInt(answers[k]);
        });

        const csrfToken = document.querySelector('input[name="_token"]').value;
        const saveLifeValuesRoute = "/life-values-submit/save";

        fetch(saveLifeValuesRoute, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ scores: counts })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            }
        })
        .catch(err => {
            console.error("Error saving life values:", err);
        });
    }
document.addEventListener("DOMContentLoaded", () => {
    localStorage.removeItem("answers");
    answers = {};
    currentQuestionNum = 1;
    maxReachedQuestion = 1;
});
</script>

<style>
/* Add styles for disabled submit button */
#submitBtn.disabled {
    background: #cccccc;
    color: #666666;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

#submitBtn:hover:not(.disabled) {
    background: #0ca6d4;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(28, 194, 242, 0.4);
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
</style>
