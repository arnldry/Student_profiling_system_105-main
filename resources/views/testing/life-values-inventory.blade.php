<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="/vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="/vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="/vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="/vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="/src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="/vendors/styles/style.css" />
		<link rel="stylesheet" type="text/css" href="/css/life-values.css" />

	<style>

        
		/* Progress Bar */
		.progress-container {
			width: 100%;
			height: 6px;
			background-color: #e0e7ff;
			border-radius: 10px;
			margin-bottom: 15px;
			overflow: hidden;
		}

		.progress-bar {
			height: 100%;
			background: linear-gradient(90deg, #1cc2f2, #0089b7);
			width: 0%;
			transition: width 0.3s ease-in-out;
			border-radius: 10px;
		}

		/* Scroll instruction */
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

		/* Question container scroll */
		#questionContainer {
			max-height: 400px;
			overflow-y: auto;
			padding: 20px;
			margin: 20px 0;
			scroll-behavior: smooth;
			scrollbar-width: none;
		}

		#questionContainer::-webkit-scrollbar {
			display: none;
		}

		/* Question card styles for scroll */
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

		.question-text {
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



		/* Submit section */
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

		#submitBtn:hover {
			background: #0ca6d4;
			transform: translateY(-2px);
			box-shadow: 0 6px 16px rgba(28, 194, 242, 0.4);
		}


body {
    font-family: "Inter", sans-serif;
    background: linear-gradient(135deg, #e8f7ff, #ffffff);
    margin: 0;
    padding: 20px 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
}

.main {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 26px rgba(0, 0, 0, 0.08);
    padding: 20px;
    max-width: 1100px;
    width: 100%;
    animation: fadeIn 0.6s ease-in-out;
}

/* Header */
h2 {
    text-align: center;
    color: #004d7a;
    font-weight: 700;
    margin-bottom: 35px;
}


.rating-scale {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.scale-label {
    flex: 1;
    text-align: center;
    font-weight: 600;
    color: #2c3e50;
}

.scale-middle {
    flex: 1;
    text-align: center;
}

.scale-middle span {
    font-weight: 600;
    color: #2c3e50;
}

.rating-explanation {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.rating-level {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 10px;
    transition: transform 0.2s ease;
}

.rating-level:hover {
    transform: translateX(5px);
    background: #f0f7ff;
}

.rating-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    font-weight: bold;
    margin-right: 1rem;
    font-size: 1.1rem;
}

.rating-desc {
    font-weight: 600;
    color: #2c3e50;
    width: 180px;
    font-size: 1rem;
}

.rating-detail {
    color: #6c757d;
    font-size: 0.9rem;
    flex: 1;
    padding-left: 1rem;
}

@media (max-width: 768px) {
    .rating-level {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }

    .rating-number {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }

    .rating-desc {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .rating-detail {
        padding-left: 0;
    }
}

/* Question Card */
.question-card {
    background: white;
    border-radius: 12px;
    padding: 2rem 2.5rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    border: 1px solid rgba(0, 123, 255, 0.1);
    position: relative;
    overflow: hidden;
}

.question-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.12);
}

.question-text-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 0.5rem;
    width: 100%;
    max-width: 750px;
    position: relative;
}

.question-number {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0056b3;
    margin-bottom: 1rem;
    background: #e6f3ff;
    padding: 0.3rem 1rem;
    border-radius: 12px;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0, 86, 179, 0.1);
}

.question-text {
    font-size: 1.75rem;
    color: #1a1a1a;
    line-height: 1.4;
    margin-bottom: 0.25rem;
    font-weight: 600;
    text-align: center;
    max-width: 700px;
    letter-spacing: -0.01em;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    word-wrap: break-word;
    overflow-wrap: break-word;
}
.radio-group {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    width: 100%;
    max-width: 400px;
    padding: 0.5rem;
    margin-top: 0.5rem;
}

/* Each radio option */
.radio-option {
    position: relative;
    flex: 0 0 auto;
}

/* Hide default radio */
.radio-option input[type="radio"] {
    display: none;
}

/* Label style */
.radio-option label {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px; /* bigger for mobile */
    height: 60px; /* bigger for mobile */
    border: 2px solid #007bff;
    border-radius: 50%;
    color: #007bff;
    font-weight: bold;
    font-size: 1.25rem; /* bigger number for touch */
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
    text-align: center;
}

/* Checked state */
.radio-option input[type="radio"]:checked + label {
    background: #007bff;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

/* Hover/focus effect */
.radio-option label:hover {
    background: rgba(0, 123, 255, 0.08);
    transform: scale(1.05);
}

/* Pagination buttons */
#prevBtn, #nextBtn, #submitBtn {
    min-width: 120px;
    font-weight: 600;
}

/* Page info */
#page-info {
    margin: 10px 0 20px;
    font-weight: 500;
    color: #004d7a;
}

/* Error message */
#error-message {
    color: #d9534f;
    font-weight: 500;
    text-align: center;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .rating-scale {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }

    .scale-label, .scale-middle {
        margin-bottom: 0.5rem;
    }

    .value-card {
        flex-direction: column;
        align-items: flex-start;
    }
    .value-text {
        margin-bottom: 15px;
    }
	.radio-group {
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }

    .radio-option label {
        width: 50px;
        height: 50px;
        font-size: 1.1rem;
    }
        .responsive-header {
        display: none;
    }
}


    </style>

	</head>
	<body>



		<div class="main">
			<div id="instructionSection" class="p-4 bg-light rounded shadow-sm">
				<h2 class="h3 mb-3 text-primary text-center">LIFE VALUES INVENTORY</h2>
				<p class="text-center mb-4"><em>An Assessment of Values that guides behavior and decision making</em></p>

				<h5 class="mb-2 text-uppercase fw-bold">Section I</h5>
				<p class="mb-3">
				Values are beliefs that influence people’s behavior and decision-making. For example, if people believe
				that telling the truth is very important, they will try to be truthful when they deal with other people.
				</p>
				<p class="mb-3">
				On the following pages is a list of beliefs that guides people’s behavior and helps them make important
				decisions. Read each one and then choose the response (1-5) that best describes how often the belief guides
				your behavior.
				</p>

				<div class="table-responsive mb-3">
					<table class="table table-bordered text-center">
						<thead class="table-secondary">
							<tr>
								<th>Almost Never<br>Guides My Behavior</th>
								<th>Sometimes<br>Guides My Behavior</th>
								<th>Almost Always<br>Guides My Behavior</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<td>1</td>
							<td>2 3 4</td>
							<td>5</td>
							</tr>
						</tbody>
					</table>
				</div>

				<p class="mb-3">
					1. Being healthy 1 2 3 4 5
					</p>
					<p class="mb-3">
						If a belief in being healthy almost never guides your behavior, circle 1. If being healthy almost always
						guides your behavior, circle 5. If the best answer for you is between 1 and 5, circle the number 2, 3, or 4 that most
						accurately describes how this belief guides your behavior.
					</p>
					<p class="mb-3">
						Now you are ready to begin. Read each item carefully and circle only one response. Usually your first
						idea is the best indicator of how you feel. Answer every item. There are no right or wrong answers. Your choices
						should describe your own values, not the values of others.
				</p>
				


				<div class="text-center mt-4">
					<button type="button" id="startBtn" class="btn btn-primary me-2">Start</button>
					<a href="{{ route('student.testingdash') }}" class="btn btn-secondary">Back</a>
				</div>
			</div>


				<!-- Result Section -->
			

			<form id="lifeValuesForm" method="POST" action="{{ route('life-values-submit.save') }}" style="display:none;">
					@csrf
					<div class="scroll-instruction">
							<p>↑ Scroll up to go back to the previous question</p>
						</div>
						
                    <div class="rating-scale">
                    <div class="scale-label">Almost Never Guides My Behavior (1)</div>
                    <div class="scale-middle">
                        <span>Sometimes Guides My Behavior (2-3-4)</span>
                    </div>
                    <div class="scale-label">Almost Always Guides My Behavior(5)</div>
                </div>

					<div class="progress-container">

						<div class="progress-bar"></div>

					</div>
				<div id="questionContainer"></div>
			</form>
				@include('partials.life-values-inventory-script')
	        </div>
		</div>



	</body>
</html>
